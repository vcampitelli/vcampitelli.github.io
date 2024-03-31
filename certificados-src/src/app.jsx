import {useState} from 'preact/hooks';
import get from 'axios';
import favicon from '/favicon.png';
import API_URL from './settings.js';

/**
 *
 * @param {{company: string, name?: string, names?: string[], subject: string, workload: number, dates: string}} data
 * @returns {JSXInternal.Element|null}
 * @constructor
 */
const Card = ({data}) => {
    if (!data) {
        return null;
    }

    if (data.company) {
        return (
            <div>
                <div className="card bg-dark mb-0 overflow-hidden">
                    <div className="card-header bg-success d-md-flex align-items-center text-white p-4">
                        <div className="flex-shrink-0">
                            <span className="bg-white text-success fs-2 mb-2 mb-md-0 me-md-3 rounded-circle card-icon">
                                ✓
                            </span>
                        </div>
                        <div>
                            <p className="fs-5 fw-bold mb-0">Certificado válido</p>
                        </div>
                    </div>
                    <div id="certificate-details" className="card-body">
                        {(data.name) ? (<div className="certificate-detail"><b>Nome</b> {data.name}</div>) : null}
                        <div className="certificate-detail"><b>Empresa</b> {data.company}</div>
                        <div className="certificate-detail"><b>Conteúdo</b> {data.subject}</div>
                        <div className="d-flex">
                            <div className="certificate-detail"><b>Carga Horária</b> {data.workload} horas</div>
                            <div className="certificate-detail ms-5"><b>Período</b> {data.dates}</div>
                        </div>
                        {(data.names) ? (
                            <div className="certificate-detail"><b>Participantes</b> {data.names.join(', ')}</div>
                        ) : null}
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className="card bg-danger">
            <div className="card-body d-sm-flex align-items-center text-white p-4">
                <div className="flex-shrink-0">
                    <span className="bg-white text-danger fs-2 me-3 rounded-circle card-icon">
                        ×
                    </span>
                </div>
                <p className="fs-5 fw-bold mb-0">Certificado inválido</p>
            </div>
        </div>
    );
}

export function App() {
    const [loading, setLoading] = useState(false);
    const [code, setCode] = useState('');
    const [data, setData] = useState(null);

    const handleSubmit = (e) => {
        if (code) {
            setLoading(true);
            get(`${API_URL}/${code}`).then((response) => {
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
    };

    return (
        <div id="container" className="container my-5 py-5">
            <div className="d-md-flex align-items-center mb-5">
                <a href="/">
                    <img src={favicon} alt="Vinícius Campitelli" />
                </a>
                <div className="ms-md-3 mt-3 mt-md-0">
                    <h1 className="mb-md-0 lh-1">Certificado de conclusão</h1>
                    <p className="fs-5 text-white-50 mb-0">
                        Verifique um certificado de meus treinamentos
                    </p>
                </div>
            </div>
            <div className="card bg-dark mb-5">
                <div className="card-body p-4">
                    <form className="d-md-flex align-items-center" method="post" onSubmit={handleSubmit}>
                        <label htmlFor="form-code" className="form-label text-md-nowrap mb-2 mb-md-0">
                            Código de verificação:
                        </label>
                        <input type="text" className="form-control mx-md-3 mb-2 mb-md-0" id="form-code" name="code"
                               placeholder="Código exibido no certificado" value={code} disabled={loading} autoFocus
                               onInput={(e) => setCode(e.target.value.trim().toUpperCase())} required />
                        <button type="submit" className="btn btn-primary mb-2 mb-md-0" disabled={loading}>
                            Verificar
                        </button>
                    </form>
                </div>
            </div>
            <div className={`text-center ${(loading) ? "d-block" : "d-none"}`}>
                <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
            </div>
            {(loading || !data) ? null : (<Card data={data} />)}
            <footer className="mt-5 py-3 text-center small">
                <a href="/">viniciuscampitelli.com</a>
            </footer>
        </div>
    );
}
