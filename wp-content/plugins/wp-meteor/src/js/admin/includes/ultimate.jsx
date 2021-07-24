/**
 *   WP Meteor Wordpress Plugin
 *   Copyright (C) 2020  Aleksandr Guidrevitch
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

import React from 'react';
import Slider from 'react-slider';

import styled from 'styled-components';

const StyledTrack = styled.div`
    background: ${props => props.value > 1 ? '#08CE69' : '#FEA502'};
`;

const Track = (props, state) => <StyledTrack {...props} value={state.value} />;
const Thumb = (props, state) => <div {...props}>{state.valueNow > 2 ? '∞' : state.valueNow}</div>;

const labels = [
    '1 second delay',
    '2 seconds delay',
    'Delay until first interaction'
];
export default class Simple extends React.Component {
    constructor(props) {
        super(props)
        this.state = { ...props.settings };
        if (!this.state.enabled) {
            this.state.delay = 1;
        }
    }
    onChange = (delay) => {
        this.setState({ delay: delay });
    }
    render() {
        return (
            <ul>
                <li>
                    <span className="enabled">

                        <Slider
                            id={this.props.prefix + "-id"}
                            className="slider"
                            defaultValue={this.state.delay}
                            onChange={this.onChange}
                            min={1}
                            max={3}
                            renderTrack={Track}
                            renderThumb={Thumb}
                        />
                        <label htmlFor={this.props.prefix + "-id"}>
                            {labels[this.state.delay - 1]}
                        </label>
                        <input type="hidden" name={this.props.prefix + '[delay]'} value={this.state.delay}></input>
                        <input type="hidden" name={this.props.prefix + '[enabled]'} value={true}></input>
                    </span>
                </li>
            </ul>
        );
    }
}
