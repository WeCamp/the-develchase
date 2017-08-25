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
	id: 4,
	name: 'React',
	checked: true,
}, {
	id: 5,
	name: 'Something else',
	checked: false,
}];

class TopicSelector extends Component {
	constructor(props) {
		super(props);
		// this.handleChange = this.handleChange.bind(this);
		this.state = { value: "", topics: AllTopics };
		console.log("state", this.state );
	}

	// handleSubmit(event) {
	// 	console.log("state", this.state );
	// 	event.preventDefault();
	// }
	// handleChange(event) {
	// 	console.log( "handlechange", event );
	// 	this.setState({value: event.target.value});
	// }
	componentDidUpdate(prevProps, prevState) {
	console.log("state after change", this.state );
}

	render(){
		return (
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
										onClick={ ()=> {  topic.checked = !topic.checked;
											console.log( "checked?", topic.checked );
										} }
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