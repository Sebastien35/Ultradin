import React from "react";

const Navbar = () => {
  // Déterminer dynamiquement la redirection
  const currentPath = window.location.pathname; // Récupère le chemin actuel
  console.log(currentPath);
  let buttonHref = "";
  let buttonContent = "";
  if (currentPath == "/") {
    buttonHref = "/login";
    buttonContent = "Login";
  } else if (currentPath == "/login"){
    buttonHref = "/register";
    buttonContent = "Register";
  } else if (currentPath == "/register"){
    buttonHref = "/login";
    buttonContent = "Login";
  } else {
    buttonHref = "/login";
    buttonContent = "Login";
  }

  return (
    <nav style={styles.navbar}>
      {/* Logo */}
      <div style={styles.logo}>LOGO</div>

      {/* Search Bar */}
      <div style={styles.searchBar}>
        <input
          type="text"
          placeholder="Search Bar"
          style={styles.searchInput}
        />
      </div>

      {/* Links */}
      <div style={styles.rightNav}>
        <div style={styles.links}>
          <a href="/" style={styles.link}>
            Accueil
          </a>
          <a href="#" style={styles.link}>
            Link 2
          </a>
          <a href="/contact" style={styles.link}>
            Contact
          </a>
          <a href="/about" style={styles.link}>
            About Us
          </a>
        </div>

        {/* Panier Button */}
        <div style={styles.panier}>
          <a style={styles.button} href={buttonHref}>
            {buttonContent}
          </a>
        </div>
      </div>
    </nav>
  );
};

const styles = {
  navbar: {
    display: "flex",
    alignItems: "center",
    padding: "15px 20px",
    fontFamily: "Arial, sans-serif",
    backgroundColor: "white",
  },
  logo: {
    fontWeight: "bold",
    fontSize: "18px",
    marginRight: "4%",
  },
  searchBar: {
    margin: "0 20px",
    width: "20%",
    marginRight: "7%",
  },
  searchInput: {
    width: "100%",
    padding: "5px",
    border: "1px solid #ccc",
    borderRadius: "4px",
  },
  links: {
    display: "flex",
    gap: "15px",
  },
  link: {
    textDecoration: "none",
    color: "black",
    fontWeight: "500",
  },
  panier: {},
  button: {
    padding: "7px 15px",
    border: "1px solid black",
    borderRadius: "4px",
    backgroundColor: "white",
    cursor: "pointer",
    textDecoration: "none",
    color: "black",
  },
  rightNav: {
    width: "100%",
    display: "flex",
    alignItems: "center",
    justifyContent: "space-between",
  },
};

export default Navbar;
