import { useState } from 'react'
import Dashboard from './components/Dashboard'
import AppointmentList from './components/AppointmentList'
import AppointmentForm from './components/AppointmentForm'

// Vues disponibles
const VIEWS = {
  DASHBOARD: 'dashboard',
  LIST: 'list',
  NEW: 'new',
}

export default function App() {
  const [view, setView] = useState(VIEWS.DASHBOARD)

  return (
    <div className="app">
      <header className="app-header">
        <h1 className="app-title">PneuTracker</h1>
        <nav className="app-nav">
          <button
            className={`nav-btn ${view === VIEWS.DASHBOARD ? 'active' : ''}`}
            onClick={() => setView(VIEWS.DASHBOARD)}
          >
            Dashboard
          </button>
          <button
            className={`nav-btn ${view === VIEWS.LIST ? 'active' : ''}`}
            onClick={() => setView(VIEWS.LIST)}
          >
            Rendez-vous
          </button>
          <button
            className={`nav-btn ${view === VIEWS.NEW ? 'active' : ''}`}
            onClick={() => setView(VIEWS.NEW)}
          >
            + Nouveau
          </button>
        </nav>
      </header>

      <main className="app-main">
        {view === VIEWS.DASHBOARD && <Dashboard />}
        {view === VIEWS.LIST && <AppointmentList />}
        {view === VIEWS.NEW && (
          <AppointmentForm onCreated={() => setView(VIEWS.LIST)} />
        )}
      </main>
    </div>
  )
}
