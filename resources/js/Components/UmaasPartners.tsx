import { Image } from "react-bootstrap";

export function UmaasPartners() {

  const imagePaths: string[] = [
    '../partners/avanti.png',
    '../partners/british gas.png',
    '../partners/british gas lite.png',
    '../partners/corona energy.png',
    '../partners/crown gas.png',
    '../partners/d-energi.png',
    '../partners/drax.png',
    '../partners/dyce.png',
    '../partners/engie.png',
    '../partners/eon.png',
    '../partners/eon-next.png',
    '../partners/n-power.png',
    '../partners/opus.png',
    '../partners/pozitive.png',
    '../partners/scottish.png',
    '../partners/sefe.png',
    '../partners/smartenergy.png',
    '../partners/sse.png',
    '../partners/total.png',
    '../partners/ugp.png',
    '../partners/valda.png',
    '../partners/ygp.png',
    '../partners/yu.png',
    '../partners/OE.png',
    '../partners/edf.png',
  ];

  return (
    <div className="bg-grey pt-5 pb-5 d-grid" style={{ justifyContent: "center" }}>
      <div className="container text-center">
        <h2 className="text-center"><b>UMaaS</b> Energy Partners</h2>
        <div className="row mt-5 mb-5 gap-0">
          {imagePaths.map((imagePath, index) => (
            <div key={index} className="col-lg-2 col-md-3 col-6 text-center mb-3 mt-4">
              <Image src={imagePath} width={140} />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}