import {useState} from 'preact/hooks';
import get from 'axios';
import favicon from '/favicon.png';
import API_URL from './settings.js';

export function App() {
    const [loading, setLoading] = useState(false);
    const [code, setCode] = useState('');
    const [data, setData] = useState(null);

    return (
        <div id="container" className="container my-5 py-5">
            <div className="d-flex align-items-center mb-5">
                <a href="/">
                    <img src={favicon} alt="Vinícius Campitelli"/>
                </a>
                <div className="ms-3">
                    <h1 className="mb-0 lh-1">Certificado de conclusão</h1>
                    <p className="fs-4 text-white-50 mb-0">Verifique a veracidade de um certificado de meus treinamentos</p>
                </div>
            </div>
            <div className="card bg-dark mb-5">
                <div className="card-body p-4">
                    <form className="d-flex align-items-center" method="post" onSubmit={(e) => {
                        if (code) {
                            setLoading(true);
                            get(API_URL + '/' + code).then((response) => {
                                setData({
                                    status: true,
                                    ...response.data,
                                });
                            }).catch((err) => {
                                setData({
                                    status: false,
                                });
                                console.error(err);
                            }).finally(() => {
                                setLoading(false);
                            });
                        }
                        e.preventDefault();
                    }}>
                        <label htmlFor="form-code" className="form-label mb-0 text-nowrap">
                            Código de verificação
                        </label>
                        <input type="text" className="form-control mx-3" id="form-code" required minLength={8}
                               maxLength={8} placeholder="00000000" value={code} disabled={loading}
                               onChange={(e) => setCode(e.target.value.trim().toUpperCase())}/>
                        <button type="submit" className="btn btn-primary" disabled={loading}>
                            Verificar
                        </button>
                    </form>
                </div>
            </div>
            <div className={"text-center" + ((loading) ? " d-block" : " d-none")}>
                <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
            </div>
            {(loading || !data) ? null : ((data?.company) ? (
                    <div className="card bg-success">
                        <div className="card-body d-flex align-items-center text-white">
                            <span className="bg-white text-success fs-1 me-3 rounded-circle card-icon">✓</span>
                            <div>
                                <p className="fs-5 fw-bold mb-2">Certificado válido</p>
                                <p className="mb-1"><b>Empresa:</b> {data.company}</p>
                                <p className="mb-1"><b>Conteúdo:</b> {data.subject}</p>
                                <p className="mb-1"><b>Carga horária:</b> {data.workload} horas</p>
                                <p className="mb-0"><b>Realização:</b> {data.dates}</p>
                            </div>
                        </div>
                    </div>
                ) : (
                    <div className="card bg-danger">
                        <div className="card-body d-flex align-items-center text-white">
                            <span className="bg-white text-danger fs-1 me-3 rounded-circle card-icon">×</span>
                            <p className="fs-5 fw-bold mb-2">Certificado inválido</p>
                        </div>
                    </div>
                )
            )}
            <footer className="mt-2 py-2 text-center">
                <a href="/">viniciuscampitelli.com</a>
            </footer>
        </div>
    );
}
