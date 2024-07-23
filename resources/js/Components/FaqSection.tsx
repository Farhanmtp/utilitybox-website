import Accordion from 'react-bootstrap/Accordion';
import faq from "../data/faq.json";
import ReactHtmlParser from 'react-html-parser';

export function FaqSection() {
    return (
        <Accordion>
            {faq.map((item) => (
                <Accordion.Item eventKey={item.id.toString()} key={item.id}>
                    <Accordion.Header>{item.id}. {item.title}</Accordion.Header>
                    <Accordion.Body>{ReactHtmlParser(item.meta ?? '')}</Accordion.Body>
                </Accordion.Item>
            ))}
        </Accordion>
    );
}
