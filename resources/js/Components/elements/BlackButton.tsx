import { Button } from "react-bootstrap";

interface Props {
  title: string;
}

export function BlackButton({ title }: Props) {
  return (
    <Button
      className="bg-black rounded-1 input-field input-field-btn mt-3"
      style={{
        border: 0,
        paddingLeft: 45,
        paddingRight: 45,
        paddingTop: 12,
        paddingBottom: 12,
        position: "relative",
      }}
    >
      {title}
      <span
        style={{
          position: "absolute",
          top: "50%",
          right: -20, // Adjust the value as needed
          transform: "translateY(-50%)",
          fontSize: 18, // Adjust the size as needed
          opacity: 0, // Initially hide the arrow
        }}
      >
        ➔
      </span>
    </Button>
  );
}
