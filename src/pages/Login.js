import React, {Component} from 'react';
import {Link} from 'react-router-dom';
import ReCAPTCHA from "react-google-recaptcha";
import axios from 'axios';
import qs from 'qs';

export default class Login extends Component {

    state = {
        password: {
            value: '',
            touched: false,
            valid: false,
        },
        email: {
            value: '',
            touched: false,
            valid: false,
        },
        captcha: {
            valid: false,
            value: null,
        }
    };

    handlePasswordChange(password) {
        this.setState({password: {value: password, touched: true, valid: password.length >= 6}});
    }

    handleEmailChange(email) {
        const validateEmailRegEx = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        this.setState({email: {value: email, touched: true, valid: validateEmailRegEx.test(email)}})
    }

    //check captcha for being valid
    handleCaptcha(value) {
        this.setState({captcha: {valid: true, value: value}})
    }

    async login() {
        const {password, email, captcha} = this.state;

        if (!process.env.REACT_APP_API_URL) {
            throw new Error('REACT_APP_API_URL missing')
        }

        try {
            //make the request to the endpoint for logging in
            const response = await axios.post(`${process.env.REACT_APP_API_URL}/user/login.php`, qs.stringify({
                email: email.value,
                password: password.value,
                captcha: captcha.value,
            }));
            //console.log(response);
            localStorage.setItem('AUTH_TOKEN', response.data.token);
            if (response.status === 200 && !response.data.requestChangePassword) {
                window.location.href = '/';
            } else {
                window.location.href = '/change-password';
            }
        } catch (err) {
            console.log(err);
            this.setState({
                isErrorShown: true
            })
        }
    }

    render() {
        const {password, email, captcha} = this.state;

        return (
            <div className="section">
                <div className="columns">
                    <div className="column is-4 is-offset-4">
                        <div className="box">
                            <div className="field">
                                <div className="control">
                                    <div className="content">
                                        <h4>Login</h4>
                                        <p>Please login in order to continue using our blog!</p>
                                        <p>If you do not have an account, head over to the <Link
                                            to="signup">signup</Link> page.</p>
                                    </div>
                                </div>
                            </div>
                            <div className="field">
                                <div className="control">
                                    <input placeholder="Email"
                                           className={`input ${email.touched && !email.valid && 'is-danger'}`}
                                           value={email.value} onChange={e => this.handleEmailChange(e.target.value)}/>
                                </div>
                            </div>
                            <div className="field">
                                <div className="control">
                                    <input placeholder="Password"
                                           className={`input ${password.touched && !password.valid && 'is-danger'}`}
                                           value={password.value}
                                           onChange={e => this.handlePasswordChange(e.target.value)}
                                           type="password"/>
                                </div>
                            </div>
                            <div className="field">
                                <div className="control">
                                    <div className="content">
                                        <p><Link
                                            to="forgot-password">Forgot Password?</Link></p>
                                    </div>
                                </div>
                            </div>
                            {/* sets up recaptcha connection */}
                            <div className="field">
                                <ReCAPTCHA
                                    sitekey="6LeJhYIUAAAAAEX9ME3cCXd4jOd2u9xr8FOTUCQH"
                                    onChange={value => this.handleCaptcha(value)}
                                />
                            </div>

                            <div className="field">
                                <div className="control">
                                    <button className="button" disabled={!password.valid || !email.valid || !captcha.valid}
                                            onClick={() => this.login()}>Login
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