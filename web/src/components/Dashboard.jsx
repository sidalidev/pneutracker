import { useState, useEffect } from 'react'
import { api } from '../services/api'

// Dashboard — affiche les stats du jour
export default function Dashboard() {
  const [stats, setStats] = useState(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    api.getDashboard()
      .then(setStats)
      .catch(console.error)
      .finally(() => setLoading(false))
  }, [])

  if (loading) return <p className="loading">Chargement...</p>
  if (!stats) return <p className="error">Erreur de chargement</p>

  return (
    <div className="dashboard">
      <h2>Dashboard</h2>

      <div className="stats-grid">
        <div className="stat-card">
          <span className="stat-value">{stats.todayTotal}</span>
          <span className="stat-label">RDV aujourd'hui</span>
        </div>
        <div className="stat-card">
          <span className="stat-value">{stats.upcoming}</span>
          <span className="stat-label">A venir</span>
        </div>
        <div className="stat-card stat-pending">
          <span className="stat-value">{stats.statusCounts?.pending || 0}</span>
          <span className="stat-label">En attente</span>
        </div>
        <div className="stat-card stat-confirmed">
          <span className="stat-value">{stats.statusCounts?.confirmed || 0}</span>
          <span className="stat-label">Confirmes</span>
        </div>
        <div className="stat-card stat-completed">
          <span className="stat-value">{stats.statusCounts?.completed || 0}</span>
          <span className="stat-label">Termines</span>
        </div>
        <div className="stat-card stat-cancelled">
          <span className="stat-value">{stats.statusCounts?.cancelled || 0}</span>
          <span className="stat-label">Annules</span>
        </div>
      </div>

      {stats.todayByFranchise && Object.keys(stats.todayByFranchise).length > 0 && (
        <div className="franchise-stats">
          <h3>Par centre aujourd'hui</h3>
          <ul>
            {Object.entries(stats.todayByFranchise).map(([franchise, count]) => (
              <li key={franchise}>
                <strong>{franchise}</strong> : {count} RDV
              </li>
            ))}
          </ul>
        </div>
      )}
    </div>
  )
}
