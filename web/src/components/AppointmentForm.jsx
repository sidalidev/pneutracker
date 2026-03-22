import { useState } from 'react'
import { api } from '../services/api'

// Formulaire de creation de rendez-vous
const FRANCHISES = ['Nantes Centre', 'Rennes Nord', 'Brest Ocean']

const EMPTY_FORM = {
  clientName: '',
  clientPhone: '',
  vehiclePlate: '',
  tireReference: '',
  quantity: 4,
  scheduledAt: '',
  franchise: FRANCHISES[0],
  notes: '',
}

export default function AppointmentForm({ onCreated }) {
  const [form, setForm] = useState(EMPTY_FORM)
  const [error, setError] = useState(null)
  const [submitting, setSubmitting] = useState(false)

  const handleChange = (e) => {
    const { name, value } = e.target
    setForm(prev => ({ ...prev, [name]: value }))
  }

  const handleSubmit = async (e) => {
    e.preventDefault()
    setError(null)
    setSubmitting(true)

    try {
      await api.createAppointment(form)
      setForm(EMPTY_FORM)
      onCreated?.()
    } catch (err) {
      setError('Erreur lors de la creation du rendez-vous')
      console.error(err)
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <div className="appointment-form">
      <h2>Nouveau rendez-vous</h2>

      {error && <p className="error">{error}</p>}

      <form onSubmit={handleSubmit}>
        <div className="form-grid">
          <div className="form-group">
            <label htmlFor="clientName">Nom du client</label>
            <input
              id="clientName"
              name="clientName"
              type="text"
              value={form.clientName}
              onChange={handleChange}
              required
            />
          </div>

          <div className="form-group">
            <label htmlFor="clientPhone">Telephone</label>
            <input
              id="clientPhone"
              name="clientPhone"
              type="tel"
              value={form.clientPhone}
              onChange={handleChange}
              placeholder="06 12 34 56 78"
              required
            />
          </div>

          <div className="form-group">
            <label htmlFor="vehiclePlate">Immatriculation</label>
            <input
              id="vehiclePlate"
              name="vehiclePlate"
              type="text"
              value={form.vehiclePlate}
              onChange={handleChange}
              placeholder="AB-123-CD"
              required
            />
          </div>

          <div className="form-group">
            <label htmlFor="tireReference">Reference pneu</label>
            <input
              id="tireReference"
              name="tireReference"
              type="text"
              value={form.tireReference}
              onChange={handleChange}
              placeholder="Michelin Primacy 4 205/55R16"
              required
            />
          </div>

          <div className="form-group">
            <label htmlFor="quantity">Quantite</label>
            <input
              id="quantity"
              name="quantity"
              type="number"
              min="1"
              max="8"
              value={form.quantity}
              onChange={handleChange}
              required
            />
          </div>

          <div className="form-group">
            <label htmlFor="scheduledAt">Date du rendez-vous</label>
            <input
              id="scheduledAt"
              name="scheduledAt"
              type="datetime-local"
              value={form.scheduledAt}
              onChange={handleChange}
              required
            />
          </div>

          <div className="form-group">
            <label htmlFor="franchise">Centre</label>
            <select
              id="franchise"
              name="franchise"
              value={form.franchise}
              onChange={handleChange}
            >
              {FRANCHISES.map(f => (
                <option key={f} value={f}>{f}</option>
              ))}
            </select>
          </div>

          <div className="form-group full-width">
            <label htmlFor="notes">Notes</label>
            <textarea
              id="notes"
              name="notes"
              value={form.notes}
              onChange={handleChange}
              rows={3}
            />
          </div>
        </div>

        <button
          type="submit"
          className="btn btn-primary"
          disabled={submitting}
        >
          {submitting ? 'Creation...' : 'Creer le rendez-vous'}
        </button>
      </form>
    </div>
  )
}
