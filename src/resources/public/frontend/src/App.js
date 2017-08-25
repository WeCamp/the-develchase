import React, { Component } from 'react';
import logo from './logo.svg';
import './App.css';

// class App extends Component {
//     constructor() {
//         super();
//         this.state = { items: [] };
//     }
//
//     componentDidMount() {
//         fetch(`https://httpbin.org/get`)
//             .then(result => {
//                 this.setState({items:result.json()});
//             });
//
//         console.log('hello');
//         console.log(this.state.items);
//     }
//
//     render() {
//         return (
//             <div></div>
//         );
        // return(
        //     <div>
        //         <div>Items:</div>
        //         { this.state.items.map(item=> { return <div>{item.name}</div>}) }
        //     </div>
        // );
//     }
// }

// export default App;


export default class App extends React.Component {
    constructor() {
        super();
        this.state = { items: [] };
    }

    componentDidMount() {
        fetch(`http://echo.jsontest.com/key/value/one/two`)
            .then(result => {
                this.setState({items:result.json()});
                console.log(this.state);
            });


    }

    render() {
        console.log('hello');

        return(
             <div>
                 <div>Items:</div>
                 {this.state.items.key}
             </div>
        );
    }
}