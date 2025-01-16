import React from 'react';
import { StyleSheet, Text, View, Linking, TouchableOpacity } from 'react-native';

const Footer: React.FC = () => {
  return (
    <View style={styles.footer}>
      <View style={styles.linksContainer}>
        <TouchableOpacity onPress={() => Linking.openURL('#')}>
          <Text style={styles.link}>Conditions générales d’utilisation</Text>
        </TouchableOpacity>
        <TouchableOpacity onPress={() => Linking.openURL('#')}>
          <Text style={styles.link}>Mentions légales</Text>
        </TouchableOpacity>
        <TouchableOpacity onPress={() => Linking.openURL('#')}>
          <Text style={styles.link}>Contact</Text>
        </TouchableOpacity>
      </View>
      <View style={styles.socialContainer}>
        <Text>Réseaux Sociaux</Text>
        <View style={styles.iconBox}>
          <Text>Icons</Text>
        </View>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  footer: {
    position: 'absolute',
    bottom: 0,
    left: 0,
    width: '100%',
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#ffffff',
    padding: 15,
    borderTopWidth: 1,
    borderTopColor: '#ccc',
  },
  linksContainer: {
    flexDirection: 'row',
    gap: 20,
  },
  link: {
    fontSize: 14,
  },
  socialContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 10,
  },
  iconBox: {
    justifyContent: 'center',
    alignItems: 'center',
    width: 50,
    height: 25,
    borderWidth: 1,
    borderColor: 'black',
  },
});

export default Footer;
