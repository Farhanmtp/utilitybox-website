<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Models\Messages;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

/**
 * @group Forms
 *
 * @unauthenticated
 */
class MessagesController extends ApiController
{

    /**
     * Submit Message
     *
     * Submit contact form data
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam first_name string Required if type is contact
     * @bodyParam message string Required if type is contact
     * @bodyParam attachment file For single file upload
     * @bodyParam attachments file[] For multiple files upload Example: [file]
     *
     */
    public function store(Request $request)
    {
        $type = $request->get('type', 'contact');

        $request->validate([
            'type' => ['required', 'in:bill,contact,meter_reading'],
            'sub_type' => ['nullable'],
            'first_name' => [Rule::requiredIf($type == 'contact')],
            'last_name' => ['nullable', 'string'],
            'business_name' => ['nullable', 'string'],
            'email' => ['required', 'string', 'email'],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'zipcode' => ['nullable', 'string'],
            'subject' => ['nullable', 'string'],
            'message' => [Rule::requiredIf($type == 'contact')],

            'attachment' => ['nullable', File::types(['jpg', 'jpeg', 'png', 'bmp', 'pdf'])
                ->min('1kb')->max('100mb')
            ],

            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['nullable', File::types(['jpg', 'jpeg', 'png', 'bmp', 'pdf'])
                ->min('1kb')->max('100mb')
            ],
        ], [
            'attachment.required' => 'Please upload an image',
            'attachment.mimes' => 'Only jpeg, png, bmp and pdf files are allowed',
            'attachment.max' => 'Maximum allowed size for an image is 3MB',

            'attachments.*.required' => 'Please upload an image',
            'attachments.*.mimes' => 'Only jpeg, png, bmp and pdf files are allowed',
            'attachments.*.max' => 'Maximum allowed size for an image is 3MB',
        ]);

        $first_name = $request->get('first_name');
        $last_name = $request->get('last_name');

        if (!$last_name) {
            $array = array_map('trim', explode(' ', $first_name));
            if (count($array) > 1) {
                $last_name = array_pop($array);
                $first_name = implode(' ', $array);
            }
        }

        $message = new Messages();
        $message->type = $request->get('type', 'contact');
        $message->sub_type = $request->get('sub_type');
        $message->first_name = $first_name;
        $message->last_name = $last_name;
        $message->email = $request->get('email');
        $message->business_name = $request->get('business_name');
        $message->phone = $request->get('phone');
        $message->address = $request->get('address');
        $message->city = $request->get('city');
        $message->zipcode = $request->get('zipcode');
        $message->subject = $request->get('subject');
        $message->message = $request->get('message');

        Log::info('Multiple upload',$request->all());
        
        $message->save();
        if ($message->id) {
            $files = [];
            if ($request->hasFile('attachment')) {
                $files[] = $this->uploadFile($request->file('attachment'));
            } else {
                if ($request->hasFile('attachments')) {
                    $attachments = $request->file('attachments');
                    foreach ($attachments as $file) {
                        $files[] = $this->uploadFile($file);
                    }
                }
            }

            $files = array_filter($files);

            if (!empty($files)) {
                $message->attachment = $files;
                $message->save();
            }

            return $this->successResponse($message, 'Form submit successfully.');
        } else {
            return $this->errorResponse('Form not submit successfully', 200);
        }
    }

    protected function uploadFile(UploadedFile $attachment)
    {
        try {
            $fileExt = $attachment->getClientOriginalExtension();
            //$fileName = $attachment->getClientOriginalName();

            $fileName = md5(uniqid()) . '.' . strtolower($fileExt);

            $upload = $attachment->move(storage()->path('forms'), $fileName);
            if ($upload) {
                return $fileName;
            }
        } catch (\Exception $e) {
        }
        return null;
    }
}
