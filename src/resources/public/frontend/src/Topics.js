import React, { Component } from 'react';
import styled from "styled-components";

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
	key: '1',
	name: 'Angular',
	description: 'Totally awesome',
	location: 'De Kluut, The Netherlands',
	url: 'http://www.wecamp.com',
}, {
	key: '2',
	name: 'PHP',
}, {
	key: '3',
	name: 'Docker',
}, {
	key: '4',
	name: 'React',
}, {
	key: '5',
	name: 'Something else',
}];

class TopicSelector extends Component {
	constructor(props) {
		super(props);
		this.state = {topics: AllTopics};
		console.log("state", this.state );
	}

	handleSubmit(event) {
		console.log("state", this.state );
		event.preventDefault();
	}

	handleChange(event) {
		// this.setState({value: event.target.value});
		console.log("state", this.state );
	}

	render(){
		return (
			<form onSubmit={this.handleSubmit}>
				{ this.state.topics.map( function( topic ) {
						return (
							<TopicItem>
								<TopicName>{ topic.name }
									<TopicCheckbox
										name={ topic.name }
										id={ topic.name }
										component="input"
										type="checkbox"
										onClick={ ()=>{console.log("Checked")}}
									/>
								</TopicName>
							</TopicItem>
						)
					}
				)}
				<div>
					<button type="submit">
						Submit
					</button>
					<button type="button">
						Clear Values
					</button>
				</div>
			</form>
		)
	}
}

export default TopicSelector;