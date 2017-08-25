import React from 'react'
import styled from "styled-components";

const columns = [{
	key: '1',
	title: 'Name',
	dataIndex: 'name',
}, {
	key: '2',
	title: 'Description',
	className: 'description',
	dataIndex: 'description',
}, {
	key: '3',
	title: 'Location',
	dataIndex: 'location',
}, {
	key: '4',
	title: 'Buy ticket',
	dataIndex: 'url',
}];

const conferences = [{
	key: '1',
	name: 'WeCamp',
	description: 'Totally awesome',
	location: 'De Kluut, The Netherlands',
	url: 'http://www.wecamp.com',
}, {
	key: '2',
	name: 'PHPBenelux',
	description: 'Just give it a try',
	location: 'Amsterdam',
	url: 'http://www.phpbenelux.com',
}, {
	key: '3',
	name: 'WeCamp',
	description: 'Still awesome',
	location: 'Some Island',
	url: 'http://www.wecamp.com',
}];

export function ConferencesResult() {
	return (
		<table>
			<tbody>
				<tr>
					{ columns.map( function( column ) {
							return (
								<td><strong>{ column.title }</strong></td>
							)
						}
					)}
				</tr>
				{ conferences.map( function( conference ){
					return (
						<tr>
							<td>{ conference.name }</td>
							<td>{ conference.description }</td>
							<td>{ conference.location }</td>
							<td><a href={ conference.url }>Order now</a></td>
						</tr>
					)
				})}
			</tbody>
		</table>

	)
}
