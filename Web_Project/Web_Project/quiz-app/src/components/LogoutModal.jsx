import { useState } from 'react'

function LogoutModal({ show, onConfirm, onCancel }) {
  if (!show) return null

  return (
    <>
      <div className="modal-backdrop fade show" onClick={onCancel}></div>
      <div className="modal fade show d-block" tabIndex="-1" role="dialog">
        <div className="modal-dialog modal-dialog-centered" role="document">
          <div className="modal-content border-0 shadow">
            <div className="modal-header border-bottom-0 pb-0">
              <h5 className="modal-title fw-bold">Confirm Logout</h5>
              <button type="button" className="btn-close" onClick={onCancel} aria-label="Close"></button>
            </div>
            <div className="modal-body pt-2">
              <p className="text-muted mb-0">Are you sure you want to log out of your account?</p>
            </div>
            <div className="modal-footer border-top-0">
              <button type="button" className="btn btn-outline-secondary" onClick={onCancel}>
                Cancel
              </button>
              <button type="button" className="btn btn-danger" onClick={onConfirm}>
                Logout
              </button>
            </div>
          </div>
        </div>
      </div>
    </>
  )
}

export default LogoutModal
