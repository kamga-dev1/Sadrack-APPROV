const API_BASE = 'http://localhost:3000/api';

async function apiCall(endpoint, method = 'GET', body = null) {
    const options = {
        method,
        headers: { 'Content-Type': 'application/json' }
    };
    if (body) options.body = JSON.stringify(body);
    try {
        const res  = await fetch(API_BASE + endpoint, options);
        const data = await res.json();
        if (!data.success) throw new Error(data.message);
        return data.data;
    } catch (err) {
        showError(err.message);
        return null;
    }
}

const GET  = (url)       => apiCall(url, 'GET');
const POST = (url, body) => apiCall(url, 'POST', body);
const PUT  = (url, body) => apiCall(url, 'PUT',  body);
const DEL  = (url)       => apiCall(url, 'DELETE');