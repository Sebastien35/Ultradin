import React from 'react';

const Footer: React.FC = () => {
  return (
    <footer style={styles.footer}>
      <div style={styles.linksContainer}>
        <a href="#" style={styles.link}>
          Conditions générales d’utilisation
        </a>
        <a href="#" style={styles.link}>
          Mentions légales
        </a>
        <a href="#" style={styles.link}>
          Contact
        </a>
      </div>
      <div style={styles.socialContainer}>
        <span>Réseaux Sociaux</span>
        <div style={styles.iconBox}>Icons</div>
      </div>
    </footer>
  );
};

const styles = {
  footer: {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: '1rem 2rem',
    borderTop: '1px solid #ccc',
    fontSize: '14px',
  },
  linksContainer: {
    display: 'flex',
    gap: '2rem',
  },
  link: {
    textDecoration: 'none',
    color: 'black',
  },
  socialContainer: {
    display: 'flex',
    alignItems: 'center',
    gap: '1rem',
  },
  iconBox: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    width: '50px',
    height: '25px',
    border: '1px solid black',
  },
};

export default Footer;