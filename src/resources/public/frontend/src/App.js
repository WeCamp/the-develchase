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

// const AllTopics = [{
// 	id: 1,
// 	label: 'Scrum',
// 	name: 'scrum',
// 	checked: true,
// }, {
// 	id: 2,
// 	label: 'PHP',
// 	name: 'php',
// 	checked: false,
// }, {
// 	id: 3,
// 	label: 'Docker',
// 	name: 'docker',
// 	checked: false,
// }, {
// 	id: 6,
// 	label: 'JavaScript',
// 	name: 'javascript',
// 	checked: false,
// }, {
// 	id: 4,
// 	label: 'ReactJS',
// 	name: 'react',
// 	checked: false,
// }, {
// 	id: 5,
// 	label: 'Symfony',
// 	name: 'symfony',
// 	checked: false,
// }, {
// 	id: 7,
// 	label: 'ArangoDB',
// 	name: 'arangodb',
// 	checked: false,
// }, {
// 	id: 8,
// 	label: 'Databases',
// 	name: 'databases',
// 	checked: false,
// }, {
// 	id: 9,
// 	label: 'Security',
// 	name: 'security',
// 	checked: false,
// }, {
// 	id: 10,
// 	label: 'Testing',
// 	name: 'testing',
// 	checked: false,
// }];

const AllTopics = [{"_key":"scrum","_id":"topics/scrum","_rev":"_VfiBDuy---","label":"scrum","name":"scrum"},{"_key":"mysql","_id":"topics/mysql","_rev":"_VfiBE_G---","label":"mysql","name":"mysql"},{"_key":"apis","_id":"topics/apis","_rev":"_VfiBDwq---","label":"apis","name":"apis"},{"_key":"bdd","_id":"topics/bdd","_rev":"_VfiBDpS---","label":"bdd","name":"bdd"},{"_key":"symfony","_id":"topics/symfony","_rev":"_VfiBE-2---","label":"symfony","name":"symfony"},{"_key":"ddd","_id":"topics/ddd","_rev":"_VfiBDpK---","label":"ddd","name":"ddd"},{"_key":"html","_id":"topics/html","_rev":"_VfiBEPK---","label":"html","name":"html"},{"_key":"rancher","_id":"topics/rancher","_rev":"_VfiBD8m---","label":"rancher","name":"rancher"},{"_key":"lamp","_id":"topics/lamp","_rev":"_VfiBEPe---","label":"lamp","name":"lamp"},{"_key":"architecture","_id":"topics/architecture","_rev":"_VfiBEJy---","label":"architecture","name":"architecture"},{"_key":"arangodb","_id":"topics/arangodb","_rev":"_VfiBE_O---","label":"arangodb","name":"arangodb"},{"_key":"docker","_id":"topics/docker","_rev":"_VfiBEPa---","label":"docker","name":"docker"},{"_key":"react","_id":"topics/react","_rev":"_VfiBELe---","label":"react","name":"react"},{"_key":"databases","_id":"topics/databases","_rev":"_VfiBEIa---","label":"databases","name":"databases"},{"_key":"testing","_id":"topics/testing","_rev":"_VfiBEBe---","label":"testing","name":"testing"},{"_key":"json","_id":"topics/json","_rev":"_VfiBE-y---","label":"json","name":"json"},{"_key":"security","_id":"topics/security","_rev":"_VfiBEBy---","label":"security","name":"security"},{"_key":"graphdb","_id":"topics/graphdb","_rev":"_VfiBELu---","label":"graphdb","name":"graphdb"},{"_key":"cloud-computing","_id":"topics/cloud-computing","_rev":"_VfiBDmS---","label":"cloud computing","name":"cloud computing"},{"_key":"css","_id":"topics/css","_rev":"_VfiBEPS---","label":"css","name":"css"},{"_key":"php","_id":"topics/php","_rev":"_VfiBEPO---","label":"php","name":"php"},{"_key":"javascript","_id":"topics/javascript","_rev":"_VfiBEPW---","label":"javascript","name":"javascript"},{"_key":"bear-sunday","_id":"topics/bear-sunday","_rev":"_VfiBD5q---","label":"bear.sunday","name":"bear.sunday"},{"_key":"encryption","_id":"topics/encryption","_rev":"_VfiBDmK---","label":"encryption","name":"encryption"},{"_key":"ci","_id":"topics/ci","_rev":"_VfiBEBm---","label":"ci","name":"ci"},{"_key":"laravel","_id":"topics/laravel","_rev":"_VfiBDuq---","label":"laravel","name":"laravel"},{"_key":"back-end","_id":"topics/back-end","_rev":"_VfiBEIW---","label":"back-end","name":"back-end"},{"_key":"testing-tools","_id":"topics/testing-tools","_rev":"_VfiBDii---","label":"testing tools","name":"testing tools"},{"_key":"privacy","_id":"topics/privacy","_rev":"_VfiBEB6---","label":"privacy","name":"privacy"},{"_key":"coding","_id":"topics/coding","_rev":"_VfiBEGK---","label":"coding","name":"coding"},{"_key":"graph-database","_id":"topics/graph-database","_rev":"_VfiBDmC---","label":"graph database","name":"graph database"},{"_key":"ui-design","_id":"topics/ui-design","_rev":"_VfiBEGO---","label":"ui design","name":"ui design"},{"_key":"mariadb","_id":"topics/mariadb","_rev":"_VfiBDwi--_","label":"mariadb","name":"mariadb"},{"_key":"cd","_id":"topics/cd","_rev":"_VfiBEBq---","label":"cd","name":"cd"},{"_key":"quality","_id":"topics/quality","_rev":"_VfiBEBy--_","label":"quality","name":"quality"}];

const columns = [{
    id: '1',
    title: 'Name',
    dataIndex: 'name',
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
        } );
        fetch('http://thedevelchase.com:8080/api.php?interests[]=' + topicArray.join( '&interests[]=' ), {
            // console.log()
            // body: { interests: topicArray },
            // headers: new Headers( {
            // 	'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            // })
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
											<TopicName>{ topic.label }
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
						<table width="100%">
							<tbody>
							<tr>
                                { columns.map( function( column ) {
                                        return (
											<td key={ column.id }><strong>{ column.title }</strong></td>
                                        )
                                    }
                                )}
							</tr>
                            { console.log( "Conferences", this.state.conferences ) }
                            { this.state.conferences.map( function( conference ){
                                return (
									<tr key={ conference.key }>
										<td>{ conference.conference }</td>
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
