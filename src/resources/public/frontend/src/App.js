import React, { Component } from 'react';
import './App.css';
import styled from "styled-components";
// import TopicSelector from "./Topics";
// import { ConferencesResult } from "./ConferencesResult";

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

export const TopicItem = styled.div`
	display: flex;
	justify-content: center;
	padding: 10px;
	height: 2em;
	&:hover {
		background-color: grey;
	}
`;

export const TopicName = styled.label`
	display: flex;
	justify-content: space-between;
	margin: auto 0;
	width: 200px;
`;

export const TopicCheckbox = styled.input`
	margin-right: 0 0 auto;
`;

const AllTopics = [{
	id: 1,
	name: 'Angular',
	checked: true,
}, {
	id: 2,
	name: 'PHP',
	checked: false,
}, {
	id: 3,
	name: 'Docker',
	checked: false,
}, {
	id: 6,
	name: 'JavaScript',
	checked: false,
}, {
	id: 4,
	name: 'React',
	checked: false,
}, {
	id: 5,
	name: 'Something else',
	checked: false,
}];

const columns = [{
	id: '1',
	title: 'Name',
	dataIndex: 'name',
}, {
	id: '2',
	title: 'Description',
	className: 'description',
	dataIndex: 'description',
}, {
	id: '3',
	title: 'Location',
	dataIndex: 'location',
}, {
	id: '4',
	title: 'Buy ticket',
	dataIndex: 'url',
}];

const conferences = [{
	id: '1',
	name: 'WeCamp',
	location: 'De Kluut',
	url: 'http://www.wecamp.com',
}, {
	id: '2',
	name: 'PHPBenelux',
	location: 'Amsterdam',
	url: 'http://www.phpbenelux.com',
}, {
	id: '3',
	name: 'WeCamp',
	location: 'Some Island',
	url: 'http://www.wecamp.com',
}];

class App extends Component {

    constructor() {
        super();
		this.state = { conferences: [], topics: AllTopics };
	}

	apiCall( input )
	{	var topicArray = [];
		this.state.topics.forEach( ( topic ) => {
			console.log( topic );
		if( topic.checked === true ) { topicArray.push( topic.name ); }
		console.log( "TopicArray", topicArray );
	} )
		fetch(`http://echo.jsontest.com/fruit/banana/apple/tree`, {
			method: "POST",
			body: { interests: topicArray },
		})
		.then(function (response) {
			return response.json();
		})
		.then(function (parsedData) {
			input.setState({conferences: parsedData});
			console.log( "setState", input.state );
		})
	}

  render() {
	  console.log("state", this.state);

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
					<form onSubmit={this.handleSubmit}>
						{ this.state.topics.map( function( topic ) {
								return (
									<TopicItem key={ topic.id }>
										<TopicName>{ topic.name }
											<TopicCheckbox
												name={ topic.name }
												defaultChecked={ topic.checked }
												component="input"
												type="checkbox"
												onClick={ () => { topic.checked=!topic.checked;
													console.log( topic.checked );
													that.apiCall( that );
												}
												}
											/>
										</TopicName>
									</TopicItem>
								)
							}
						)}
					</form>
			</Topics>
            <Conferences>
                <SubTitle>We recommend visiting these conferences:</SubTitle>
				<table>
					<tbody>
					<tr>
						{ columns.map( function( column ) {
								return (
									<td key={ column.id }><strong>{ column.title }</strong></td>
								)
							}
						)}
					</tr>
					{ conferences.map( function( conference ){
						return (
							<tr key={ conference.id }>
								<td>{ conference.name }</td>
								<td>{ conference.location }</td>
								<td><a href={ conference.url }>Order now</a></td>
							</tr>
						)
					})}
					</tbody>
				</table>
            </Conferences>
        </Content>
      </div>
    );
  }
}

export default App;
