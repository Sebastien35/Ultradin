// ultradinfront/app/Contexts/AuthContext.js

import React, { createContext, useState, useEffect } from 'react';
import { getToken, storeToken, removeToken } from '../../security/secureStorage'; // Adjust the path if necessary

export const AuthContext = createContext({
    token: null,
    signIn: async () => {},
    signOut: async () => {},
});

/**
 * AuthProvider component that wraps the app and provides authentication context.
 * @param {object} props - The component props.
 * @param {React.ReactNode} props.children - The child components.
 */
export const AuthProvider = ({ children }) => {
    const [token, setToken] = useState(null);
    const [isLoading, setIsLoading] = useState(true); // To handle initial loading state

    useEffect(() => {
        const loadToken = async () => {
            try {
                const storedToken = await getToken();
                if (storedToken) {
                    setToken(storedToken);
                }
            } catch (error) {
                console.error('Error loading token:', error);
            } finally {
                setIsLoading(false);
            }
        };
        loadToken();
    }, []);

    /**
     * Sign in function to store the token and update state.
     * @param {string} newToken - The JWT token received from the server.
     */
    const signIn = async (newToken) => {
        try {
            await storeToken(newToken);
            setToken(newToken);
        } catch (error) {
            console.error('Error signing in:', error);
        }
    };

    /**
     * Sign out function to remove the token and update state.
     */
    const signOut = async () => {
        try {
            await removeToken();
            setToken(null);
        } catch (error) {
            console.error('Error signing out:', error);
        }
    };

    if (isLoading) {
        return null; // You can replace this with a loading spinner if desired
    }

    return (
        <AuthContext.Provider value={{ token, signIn, signOut }}>
            {children}
        </AuthContext.Provider>
    );
};
