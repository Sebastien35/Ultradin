import NavBar from "@/components/ui/navbar";
import { AuthProvider } from './Contexts/AuthContext'; 

export default function App() {
    return (
        <AuthProvider>
            <div style={styles.body}>
                <NavBar />
                <h1>PAGE ACCUEIL</h1>
            </div>
        </AuthProvider>
    );
}

const styles = {
    body: {
        fontFamily: "Arial, sans-serif", 
        backgroundColor: "#F2F2F2",
        padding: "0px",
        margin: "0px",
    },
};
