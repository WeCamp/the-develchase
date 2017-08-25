import React, { Component } from 'react';
import './App.css';
import styled from "styled-components";
import TopicSelector from "./Topics";
import { ConferencesResult } from "./ConferencesResult";

const Header = styled.div`
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    margin: auto;
    margin-bottom: 0;
    background-color: #4ABDAC;
    height: 4em;
    `;

const Title = styled.h1`
    color: #DFDCE3;
`;

const SubTitle = styled.h2`
    color: #FC4A1A;
`;

const NavigationBar = styled.div`
    display: flex;
    justify-content: flex-start;
    align-items: center;
    width: 100%;
    margin-top: 0;
    background-color: #FC4A1A;
    border: 1px solid black;
    height: 2em;
    `;

const MenuItem = styled.div`
    width: 200px;
    // margin: auto;
    color: white;
    &:hover {
        color: black;
        }
    `;

const Content = styled.div`
    display: flex;
    justify-content: center;
    flex-direction: row;
    width: 100%;
    background-color: white;
    `;

const Topics = styled.div`
    width: 45%;
    background-color: lightgrey;
    margin: 2px;
    `;

const Conferences = styled.div`
    width: 45%;
    background-color: lightgrey;
    margin: 2px;
    `;

class App extends Component {

    constructor() {
        super();
        this.state = { items: [] };
		console.log("state", this.setState);
	}

	apiCall( input )
	{
		fetch(`http://echo.jsontest.com/fruit/banana/apple/tree`)
		.then(function (response) {
			console.log("response", response);
			return response.json();
		})
		.then(function (parsedData) {
			input.setState({items: parsedData});
			console.log( "setState", input.state );
		})
	}

  render() {
	  var that = this;

	  return (
      <div className="App">
        <Header>
          {/*<img src={logo} className="App-logo" alt="logo" />*/}
          <Title>The DevelChasers</Title>
        </Header>
          <NavigationBar>
              <MenuItem>Select your conferences</MenuItem>
              <MenuItem>People to meet</MenuItem>
          </NavigationBar>
        <Content>
            <Topics>
                <SubTitle>Select your topics</SubTitle>
                <TopicSelector apiCall={ this.apiCall } that={ that }/>
            </Topics>
            <Conferences>
                <SubTitle>We recommend visiting these conferences:</SubTitle>
                <ConferencesResult></ConferencesResult>
            </Conferences>
        </Content>
      </div>
    );
  }
}

export default App;
