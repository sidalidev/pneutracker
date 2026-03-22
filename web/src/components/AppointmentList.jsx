import { useState, useEffect } from 'react'
import { api } from '../services/api'
import FranchiseFilter from './FranchiseFilter'

// Liste des rendez-vous avec filtre par franchise
export default function AppointmentList() {
  const [appointments, setAppointments] = useState([])
  const [loading, setLoading] = useState(true)
  const [franchise, setFranchise] = useState(null)

  const loadAppointments = (selectedFranchise) => {
    setLoading(true)
    api.getAppointments(selectedFranchise)
      .then(setAppointments)
      .catch(console.error)
      .finally(() => setLoading(false))
  }

  useEffect(() => {
    loadAppointments(franchise)
  }, [franchise])

  // Changer le statut d'un rendez-vous
  const handleStatusChange = (id, newStatus) => {
    api.updateStatus(id, newStatus)
      .then(() => loadAppointments(franchise))
      .catch(console.error)
  }

  // Badge de statut avec couleur
  const statusBadge = (status) => {
    const labels = {
      pending: 'En attente',
      confirmed: 'Confirme',
      completed: 'Termine',
      cancelled: 'Annule',
    }
    return <span className={`badge badge-${status}`}>{labels[status]}</span>
  }

  return (
    <div className="appointment-list">
      <div className="list-header">
        <h2>Rendez-vous</h2>
        <FranchiseFilter onFilter={setFranchise} />
      </div>

      {loading ? (
        <p className="loading">Chargement...</p>
      ) : appointments.length === 0 ? (
        <p className="empty">Aucun rendez-vous trouve</p>
      ) : (
        <table className="appointments-table">
          <thead>
            <tr>
              <th>Client</th>
              <th>Telephone</th>
              <th>Vehicule</th>
              <th>Pneu</th>
              <th>Qte</th>
              <th>Date</th>
              <th>Centre</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {appointments.map(appt => (
              <tr key={appt.id}>
                <td>{appt.clientName}</td>
                <td>{appt.clientPhone}</td>
                <td>{appt.vehiclePlate}</td>
                <td className="tire-ref">{appt.tireReference}</td>
                <td>{appt.quantity}</td>
                <td>{appt.scheduledAt}</td>
                <td>{appt.franchise}</td>
                <td>{statusBadge(appt.status)}</td>
                <td className="actions">
                  {appt.status === 'pending' && (
                    <button
                      className="btn btn-confirm"
                      onClick={() => handleStatusChange(appt.id, 'confirmed')}
                    >
                      Confirmer
                    </button>
                  )}
                  {appt.status === 'confirmed' && (
                    <button
                      className="btn btn-complete"
                      onClick={() => handleStatusChange(appt.id, 'completed')}
                    >
                      Terminer
                    </button>
                  )}
                  {appt.status !== 'cancelled' && appt.status !== 'completed' && (
                    <button
                      className="btn btn-cancel"
                      onClick={() => handleStatusChange(appt.id, 'cancelled')}
                    >
                      Annuler
                    </button>
                  )}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  )
}
