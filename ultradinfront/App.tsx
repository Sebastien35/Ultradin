import { Stack } from "expo-router";
import { AuthProvider } from '@/app/Contexts/AuthContext';

export default function App() {
    return (
        <AuthProvider>
            <Stack />
        </AuthProvider>
    );
}
