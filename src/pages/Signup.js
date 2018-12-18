import React, {Component} from 'react';
import axios from 'axios';
import qs from 'qs';

export default class Signup extends Component {

    state = {
        password: {
            value: '',
            touched: '',
            valid: false,
        },
        email: {
            value: '',
            touched: '',
            valid: false,
        },
        avatar: {
            value: null
        },
        username: {
            value: '',
            touched: '',
            valid: false,
        }
    };

    async signUp() {
        const {email, password, avatar, username} = this.state;

        if (!process.env.REACT_APP_API_URL) {
            throw new Error('REACT_APP_API_URL missing')
        }

        try {
            await axios.post(`${process.env.REACT_APP_API_URL}/user/signup.php`, qs.stringify({
                email: email.value,
                password: password.value,
                avatar: avatar.value,
                username: username.value
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
        const validateEmailRegEx = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        this.setState({email: {value: email, touched: true, valid: validateEmailRegEx.test(email)}});
    }

    handlePasswordChange(password) {
        const validatePassNumbers = /[0-9]/;
        const validatePassCapital = /[A-Z]/;
        this.setState({password: {value: password, touched: true, valid: password.length > 6 && validatePassNumbers.test(password) && validatePassCapital.test(password)}});
    }

    handleUsernameChange(username) {
        this.setState({username: {value: username, touched: true, valid: username.length > 4}});
    }

    handleAvatarUpload(e) {
        const reader = new FileReader();
        const file = e.target.files[0];

        reader.onload = data => {
            if (data.target.result.indexOf('jpeg') > -1) {
                this.setState({avatar: {value: data.target.result}});
            }
        };

        reader.readAsDataURL(file);
    }

    render() {
        const {email, password, avatar, username} = this.state;

        return (
            <div className="section">
                <div className="columns">
                    <div className="column is-4 is-offset-4">
                        <div className="box">
                            <div className="field">
                                <div className="controk">
                                    <div className="content">
                                        <h4>Signup</h4>
                                        <p>Please fill-in the fields below in order to register.</p>
                                    </div>
                                </div>
                            </div>
                            <div className="field">
                                    <div className="file is-boxed">
                                        <label className="file-label">
                                            <input className="file-input" type="file" onChange={e => this.handleAvatarUpload(e)}/>
                                            <span className="file-cta">
                                                <span className="file-icon">
                                                    <i className="fas fa-cloud-upload-alt"/>
                                                </span>
                                                 <span className="file-label">
                                                    Avatar upload
                                                 </span>
                                            </span>
                                        </label>
                                    </div>
                            </div>
                            <div className="field">
                                <div className="control">
                                    <input placeholder="Name"
                                           className={`input ${username.touched && !username.valid && 'is-danger'}`}
                                           onChange={e => this.handleUsernameChange(e.target.value)}
                                           value={username.value}/>
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
                                <div className="control">
                                    <input placeholder="Password" value={password.value}
                                           className={`input ${password.touched && !password.valid && 'is-danger'}`}
                                           type="password"
                                           onChange={e => this.handlePasswordChange(e.target.value)}
                                    />
                                </div>
                            </div>
                            <div className="field">
                                <div className="control">
                                    <button className="button" onClick={() => this.signUp()}
                                            disabled={!email.valid || !password.valid || !username.valid || !avatar.value}>Sign
                                        Up
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