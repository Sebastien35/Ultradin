import NavBar from "@/components/ui/navbar";
import { AuthProvider } from './Contexts/AuthContext'; // Chemin corrigé

export default function App() {
    return(
        <AuthProvider>
            <NavBar/>
        </AuthProvider>
    )
}