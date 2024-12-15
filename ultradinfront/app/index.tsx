import React from "react";
import NavBar from "@/components/ui/navbar";

export default function Home() {
    return (
        <div style={styles.body}>
            <NavBar />
            <h1>PAGE ACCUEIL</h1>
        </div>
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
