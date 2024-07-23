@extends('admin.layouts.app')
@section('style')
    @stack('styles')
@endsection
@section('content')
    <div id="app" class="container-fluid ">
        <div id="body-wrapper" class="py-5 px-2">
            <div class="card">
                <div class="card-header">
                    @stack('documentTitle')
                </div>
                <main class="card-body">
                    <main class="" role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Table">
                        @yield('env-content')
                    </main>
                </main>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF"
            crossorigin="anonymous"></script>
    @stack('scripts')
@endsection
