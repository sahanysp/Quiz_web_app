import { createContext, useContext, useState, useEffect } from 'react'
import { fetchSession, loginUser, logoutUser } from '../api/client'

const AuthContext = createContext(null)

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null)
  const [isAuthenticated, setIsAuthenticated] = useState(false)
  const [loading, setLoading] = useState(true)

  const checkSession = async () => {
    try {
      const response = await fetchSession()
      if (response?.authenticated) {
        setUser(response.user)
        setIsAuthenticated(true)
      } else {
        setUser(null)
        setIsAuthenticated(false)
      }
      return response
    } catch {
      setUser(null)
      setIsAuthenticated(false)
      return null
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    checkSession()
  }, [])

  const login = async (credentials) => {
    const response = await loginUser(credentials)
    if (response?.user) {
      setUser(response.user)
      setIsAuthenticated(true)
    }
    return response
  }

  const logout = async () => {
    setUser(null)
    setIsAuthenticated(false)
    try {
      await logoutUser()
    } catch {
      // Backend call may fail, but frontend state is already cleared
    }
  }

  return (
    <AuthContext.Provider value={{ user, isAuthenticated, loading, login, logout, checkSession }}>
      {children}
    </AuthContext.Provider>
  )
}

export function useAuth() {
  const context = useContext(AuthContext)
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider')
  }
  return context
}
