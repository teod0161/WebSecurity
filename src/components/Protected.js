import React, {Component} from 'react';
import {Redirect} from 'react-router-dom';
import axios from 'axios'
import qs from 'qs';

export default (BaseComponent, restricted) => {
    return class extends Component {

        state = {
            loading: true,
            loggedIn: false,
            authToken: null
        };

        async componentWillMount() {
            const token = localStorage.getItem('AUTH_TOKEN');

            if (!process.env.REACT_APP_API_URL) {
                throw new Error('REACT_APP_API_URL missing')
            }
            let url = 'http://localhost:8888';

            if (process.env.REACT_APP_API_URL === 'http://68.183.71.52:8888/api') {
                url = 'http://68.183.71.52:8888'
            }
            try {
                await axios.post(`${url}/index.php`, qs.stringify({AUTH_TOKEN: token}));
                this.setState({loading: false, loggedIn: true, authToken: token});
            } catch (err) {
                console.log(err);
                this.setState({loading: false, loggedIn: false});
            }
        }

        render() {
            const {loading, loggedIn, authToken} = this.state;
            const props = this.props;

            return (
                loading
                    ? <h1>Loading</h1>
                    : loggedIn
                    ? <BaseComponent {...props} authToken={authToken} isLoggedIn={true}/>
                    : restricted
                        ? <Redirect to="/login"/>
                        : <BaseComponent {...props} isLoggedIn={false}/>
            );

        }
    }
}