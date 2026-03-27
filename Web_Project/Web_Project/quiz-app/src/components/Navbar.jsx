import { useState, useRef, useEffect } from 'react'
import { NavLink, Link } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import LogoutModal from './LogoutModal'

function Navbar() {
  const { isAuthenticated, user, logout } = useAuth()
  const [dropdownOpen, setDropdownOpen] = useState(false)
  const [showLogoutModal, setShowLogoutModal] = useState(false)
  const dropdownRef = useRef(null)

  // Close dropdown when clicking outside
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setDropdownOpen(false)
      }
    }
    document.addEventListener('mousedown', handleClickOutside)
    return () => document.removeEventListener('mousedown', handleClickOutside)
  }, [])

  const handleLogoutClick = () => {
    setDropdownOpen(false)
    setShowLogoutModal(true)
  }

  const handleLogoutConfirm = () => {
    setShowLogoutModal(false)
    logout()
    window.location.href = '/'
  }

  const handleLogoutCancel = () => {
    setShowLogoutModal(false)
  }

  return (
    <>
      <nav className="navbar navbar-expand-lg navbar-dark quiz-navbar">
        <div className="container-fluid px-4 px-lg-5">
          <NavLink className="navbar-brand fw-bold" to="/">
            QuizMaster
          </NavLink>
          <button
            className="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNav"
            aria-controls="mainNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span className="navbar-toggler-icon"></span>
          </button>

          <div className="collapse navbar-collapse" id="mainNav">
            <ul className="navbar-nav ms-auto align-items-center">
              {isAuthenticated ? (
                <>
                  <li className="nav-item">
                    <NavLink className="nav-link" to="/">
                      Home
                    </NavLink>
                  </li>
                  <li className="nav-item">
                    <NavLink className="nav-link" to="/quiz">
                      Quiz
                    </NavLink>
                  </li>
                  <li className="nav-item">
                    <NavLink className="nav-link" to="/results">
                      Results
                    </NavLink>
                  </li>
                  <li className="nav-item">
                    <NavLink className="nav-link" to="/contact">
                      Contact
                    </NavLink>
                  </li>

                  {/* Account Dropdown */}
                  <li className="nav-item dropdown ms-2" ref={dropdownRef}>
                    <a
                      className="nav-link dropdown-toggle d-flex align-items-center"
                      href="#"
                      role="button"
                      onClick={(e) => { e.preventDefault(); setDropdownOpen(!dropdownOpen) }}
                      aria-expanded={dropdownOpen}
                    >
                      <span className="account-avatar me-2">
                        {(user?.username || 'U').charAt(0).toUpperCase()}
                      </span>
                      {user?.username || 'Account'}
                    </a>
                    <div className={`dropdown-menu dropdown-menu-end shadow${dropdownOpen ? ' show' : ''}`} style={{ minWidth: '260px' }}>
                      <div className="px-3 py-3">
                        <div className="d-flex align-items-center mb-3">
                          <div className="account-avatar-lg me-3">
                            {(user?.username || 'U').charAt(0).toUpperCase()}
                          </div>
                          <div>
                            <h6 className="mb-0 fw-bold">{user?.username || 'User'}</h6>
                            <small className="text-muted">{user?.email || ''}</small>
                          </div>
                        </div>
                        <hr className="my-2" />
                        <Link
                          to="/dashboard"
                          className="btn btn-outline-primary w-100 mb-2"
                          onClick={() => setDropdownOpen(false)}
                        >
                          <i className="bi bi-speedometer2 me-2"></i>Dashboard
                        </Link>
                        <button
                          className="btn btn-outline-danger w-100"
                          onClick={handleLogoutClick}
                        >
                          Logout
                        </button>
                      </div>
                    </div>
                  </li>
                </>
              ) : (
                <>
                  <li className="nav-item">
                    <NavLink className="nav-link" to="/">
                      Home
                    </NavLink>
                  </li>
                  <li className="nav-item">
                    <NavLink className="nav-link" to="/login">
                      Login
                    </NavLink>
                  </li>
                  <li className="nav-item">
                    <NavLink className="nav-link" to="/register">
                      Sign Up
                    </NavLink>
                  </li>
                </>
              )}
            </ul>
          </div>
        </div>
      </nav>

      <LogoutModal
        show={showLogoutModal}
        onConfirm={handleLogoutConfirm}
        onCancel={handleLogoutCancel}
      />
    </>
  )
}

export default Navbar
