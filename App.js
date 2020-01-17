/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow
 */

import React, { Component } from 'react';
import { Platform, StyleSheet, Text, View, TouchableOpacity, Image } from 'react-native';
import { createStackNavigator, createAppContainer } from 'react-navigation';

const instructions = Platform.select({
  ios: 'Press Cmd+R to reload,\n' + 'Cmd+D or shake for dev menu',
  android:
    'Double tap R on your keyboard to reload,\n' +
    'Shake or press menu button for dev menu',
});

type Props = {};
export default class App extends Component<Props> {
  render() {
    return (
      <View style={styles.container}>
        <View style={styles.spacer}></View>
        <TouchableOpacity
          style={styles.button}
        >
          <Text style={styles['button-text']}>Sign Up</Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={styles.facebook}
        >
          <Image
            style={styles['fb-logo']}
            source={require('./facebook-letter-logo.png')}
            resizeMode="contain"
          />
          <Text style={styles['button-text']}>Log in with Facebook</Text>
        </TouchableOpacity>
        <View style={styles['bottom-text']}>
          <Text>Already have an account?</Text>
          <TouchableOpacity>
            <Text style={styles.login}>Log in</Text>
          </TouchableOpacity>
        </View>
      </View>
    );
  }
}

const styles = StyleSheet.create({
  spacer: {
    flexGrow: 1,
  },
  container: {
    flex: 1,
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#F5FCFF',
    paddingBottom: 40,
  },
  button: {
    height: 50,
    width: 250,
    borderRadius: 25,
    backgroundColor: '#333',
    marginBottom: 20,
  },
  facebook: {
    height: 50,
    width: 250,
    borderRadius: 25,
    backgroundColor: '#3b5998',
    marginBottom: 50,
  },
  'fb-logo': {
    position: 'absolute',
    left: 0,
    top: 13,
    height: 24,
  },
  'button-text': {
    fontWeight: '500',
    color: '#fff',
    textAlign: 'center',
    lineHeight: 50,
  },
  'bottom-text': {
    display: 'flex',
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    textAlign: 'center',
    color: '#333333',
    marginBottom: 5,
  },
  login: {
    fontWeight: '700',
    paddingLeft: 5,
  }
});
