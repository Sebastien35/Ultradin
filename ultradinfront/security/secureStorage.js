// secureStorage.js
import * as Keychain from 'react-native-keychain';

export const storeToken = async (token) => {
    try {
        await Keychain.setGenericPassword('jwt', token);
    } catch (e) {
        console.error('Failed to save the token securely', e);
    }
};

export const getToken = async () => {
    try {
        const credentials = await Keychain.getGenericPassword();
        if (credentials) {
            return credentials.password;
        }
        return null;
    } catch (e) {
        console.error('Failed to fetch the token securely', e);
        return null;
    }
};

export const removeToken = async () => {
    try {
        await Keychain.resetGenericPassword();
    } catch (e) {
        console.error('Failed to remove the token securely', e);
    }
};
