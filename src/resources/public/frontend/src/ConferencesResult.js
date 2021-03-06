import React from 'react'
import styled from "styled-components";

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

export function ConferencesResult() {
	return (
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

	)
}
