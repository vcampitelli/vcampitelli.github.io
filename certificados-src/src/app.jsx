import {useState} from 'preact/hooks';
import get from 'axios';
import favicon from '/favicon.png';
import API_URL from './settings.js';

const Card = ({data}) => {
    if (!data) {
        return null;
    }

    if (data.company) {
        return (
            <div className="card bg-success">
                <div className="card-body d-md-flex align-items-center text-white">
                    <span className="d-block bg-white text-success fs-1 mb-2 mb-md-0 me-md-3 rounded-circle card-icon">
                        ✓
                    </span>
                    <div>
                        <p className="fs-5 fw-bold mb-2">Certificado válido</p>
                        {(data.name) ? (<p className="mb-1"><b>Nome:</b> {data.name}</p>) : null}
                        <p className="mb-1"><b>Empresa:</b> {data.company}</p>
                        <p className="mb-1"><b>Conteúdo:</b> {data.subject}</p>
                        <p className="mb-1"><b>Carga horária:</b> {data.workload} horas</p>
                        <p className="mb-0"><b>Período:</b> {data.dates}</p>
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className="card bg-danger">
            <div className="card-body d-sm-flex align-items-center text-white">
                <span className="d-block bg-white text-danger fs-1 me-3 rounded-circle card-icon">
                    ×
                </span>
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
                    <p className="fs-4 text-white-50 mb-0">
                        Verifique a veracidade de um certificado de meus treinamentos
                    </p>
                </div>
            </div>
            <div className="card bg-dark mb-5">
                <div className="card-body p-4">
                    <form className="d-md-flex align-items-center" method="post" onSubmit={handleSubmit}>
                        <label htmlFor="form-code" className="form-label text-md-nowrap mb-2 mb-md-0">
                            Código de verificação
                        </label>
                        <input type="text" className="form-control mx-md-3 mb-2 mb-md-0" id="form-code" name="code"
                               minLength={8} maxLength={8} placeholder="00000000" value={code} disabled={loading}
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
            <footer className="mt-2 py-2 text-center">
                <a href="/">viniciuscampitelli.com</a>
            </footer>
        </div>
    );
}
