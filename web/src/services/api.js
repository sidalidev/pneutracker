// Service API — wrapper fetch pour l'API Symfony

const API_URL = '/api'

export const api = {
  getAppointments: (franchise) =>
    fetch(`${API_URL}/appointments${franchise ? `?franchise=${franchise}` : ''}`)
      .then(r => r.json()),

  getAppointment: (id) =>
    fetch(`${API_URL}/appointments/${id}`)
      .then(r => r.json()),

  createAppointment: (data) =>
    fetch(`${API_URL}/appointments`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    }).then(r => r.json()),

  updateStatus: (id, status) =>
    fetch(`${API_URL}/appointments/${id}/status`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ status }),
    }).then(r => r.json()),

  getDashboard: () =>
    fetch(`${API_URL}/dashboard`)
      .then(r => r.json()),
}
