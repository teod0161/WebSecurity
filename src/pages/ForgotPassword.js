import React, {Component} from 'react';
import axios from 'axios';
import qs from 'qs';

export default class ForgotPassword extends Component {

    state = {
        email: {
            value: '',
            touched: '',
            valid: false,
        }
    };

    async forgotPassword() {
        const {email} = this.state;

        if (!process.env.REACT_APP_API_URL) {
            throw new Error('REACT_APP_API_URL missing')
        }

        try {
            await axios.post(`${process.env.REACT_APP_API_URL}/user/forgot_password.php`, qs.stringify({
                email: email.value
            })).then(response => {
                if (response.status === 200) {
                   window.location.href = '/';
                }
            });
        } catch (err) {
            console.log(err);
        }
    }


    handleEmailChange(email) {
        this.setState({email: {value: email, touched: true, valid: email.length > 6}});
    }


    render() {
        const {email} = this.state;

        return (
            <div className="section">
                <div className="columns">
                    <div className="column is-4 is-offset-4">
                        <div className="box">
                            <div className="field">
                                <div className="control">
                                    <div className="content">
                                        <h4>Forgot Password</h4>
                                        <p>Here you can recover your password. Please provide your email address, so we
                                            can send you a temporary password!</p>
                                    </div>
                                </div>
                            </div>

                            <div className="field">
                                <div className="control">
                                    <input placeholder="Email" value={email.value}
                                           className={`input ${email.touched && !email.valid && 'is-danger'}`}
                                           onChange={e => this.handleEmailChange(e.target.value)}
                                    />
                                </div>
                            </div>

                            <div className="field">
                                <div className="control buttons">
                                    <button className="button" onClick={() => this.props.history.push('/')}>
                                        Cancel
                                    </button>
                                    <button className="button" onClick={() => this.forgotPassword()}
                                            disabled={!email.valid}>
                                        Send
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }

}