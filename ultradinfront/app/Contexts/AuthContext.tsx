import React, { createContext, useState, useEffect, ReactNode } from 'react';
import jwtDecode from 'jwt-decode';
import { getValueFor, save } from '@/security/secureStorage';

export interface DecodedToken {
  exp: number;
  username?: string;
  // add other token fields as needed
  [key: string]: any;
}

export interface AuthContextType {
  user: DecodedToken | null;
  login: (token: string) => Promise<void>;
  logout: () => Promise<void>;
}

export const AuthContext = createContext<AuthContextType | undefined>(undefined);

interface AuthProviderProps {
  children: ReactNode;
}

export function AuthProvider({ children }: AuthProviderProps): JSX.Element {
  const [user, setUser] = useState<DecodedToken | null>(null);

  useEffect(() => {
    const loadToken = async () => {
      const token = await getValueFor('token');
      if (token) {
        try {
          const decoded = jwtDecode<DecodedToken>(token);
          if (decoded.exp * 1000 > Date.now()) {
            setUser(decoded);
          } else {
            setUser(null);
          }
        } catch (error) {
          console.error('Error decoding token:', error);
          setUser(null);
        }
      }
    };

    loadToken();
  }, []);

  const login = async (token: string): Promise<void> => {
    await save('token', token);
    try {
      const decoded = jwtDecode<DecodedToken>(token);
      setUser(decoded);
    } catch (error) {
      console.error('Error decoding token during login:', error);
    }
  };

  const logout = async (): Promise<void> => {
    await save('token', '');
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
}
