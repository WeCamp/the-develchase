import React, { Component } from 'react';
import logo from './logo.svg';
import './App.css';

export default class App extends React.Component {
    constructor() {
        super();
        this.state = { items: [] };

    }

    componentDidMount() {
        var self = this;

        fetch(`http://echo.jsontest.com/fruit/banana/apple/tree`)
            .then(function(response) {
                return response.json();
            })
            .then(function(parsedData) {
                self.setState({items:parsedData});
                // resolve(parsedData);
                // console.log(parsedData);
                console.log(self.state);
            });
    }

    render() {
        return(
             <div>
                 <div>Items:</div>
                 {this.state.items.key}
             </div>
        );
    }
}