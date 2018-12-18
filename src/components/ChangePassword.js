import React, {Component} from 'react';
import axios from 'axios';
import qs from 'qs';

export default class ChangePassword extends Component {

    state = {
        currentPassword: {
            value: '',
            touched: '',
            valid: false,
        },
        newPassword: {
            value: '',
            touched: '',
            valid: false,
        },
        confirmPassword: {
            value: '',
            touched: '',
            valid: false,
        },
    };

    async changePassword() {
        const {currentPassword, newPassword, confirmPassword} = this.state;

        if (!process.env.REACT_APP_API_URL) {
            throw new Error('REACT_APP_API_URL missing')
        }
        if (window.localStorage.getItem("AUTH_TOKEN")) {
            try {
                await axios.post(`${process.env.REACT_APP_API_URL}/user/change_password.php`, qs.stringify({
                    currentPassword: currentPassword.value,
                    newPassword: newPassword.value,
                    confirmPassword: confirmPassword.value,
                    AUTH_TOKEN: window.localStorage.getItem("AUTH_TOKEN")
                })).then(response => {
                    if (response.status === 200) {
                        window.location.href = '/';
                    }
                });
            } catch (err) {
                console.log(err);
            }
        }
    }


    handleCurrentPasswordChange(password) {
        this.setState({currentPassword: {value: password, touched: true, valid: password.length > 6}});
    }

    handleNewPasswordChange(password) {
        this.setState({newPassword: {value: password, touched: true, valid: password.length > 6}});
    }

    handleConfirmPasswordChange(password) {
        this.setState({confirmPassword: {value: password, touched: true, valid: password.length > 6}});
    }


    render() {
        const {currentPassword, newPassword, confirmPassword} = this.state;

        return (
            <div className="section">
                <div className="columns">
                    <div className="column is-4 is-offset-4">
                        <div className="box">
                            <div className="field">
                                <div className="control">
                                    <div className="content">
                                        <h4>Change Password</h4>
                                        <p>Here you can change your password!</p>
                                    </div>
                                </div>
                            </div>

                            <div className="field">
                                <div className="control">
                                    <input placeholder="Current Password" value={currentPassword.value}
                                           className={`input ${currentPassword.touched && !currentPassword.valid && 'is-danger'}`}
                                           type="password"
                                           onChange={e => this.handleCurrentPasswordChange(e.target.value)}
                                    />
                                </div>
                            </div>
                            <div className="field">
                                <div className="control">
                                    <input placeholder="New Password" value={newPassword.value}
                                           className={`input ${newPassword.touched && !newPassword.valid && 'is-danger'}`}
                                           type="password"
                                           onChange={e => this.handleNewPasswordChange(e.target.value)}
                                    />
                                </div>
                            </div>
                            <div className="field">
                                <div className="control">
                                    <input placeholder="Confirm Password" value={confirmPassword.value}
                                           className={`input ${confirmPassword.touched && !confirmPassword.valid && 'is-danger'}`}
                                           type="password"
                                           onChange={e => this.handleConfirmPasswordChange(e.target.value)}
                                    />
                                </div>
                            </div>
                            <div className="field">
                                <div className="control buttons">
                                    <button className="button" onClick={() => this.props.history.push('/')}>
                                        Cancel
                                    </button>
                                    <button className="button" onClick={() => this.changePassword()}
                                            disabled={(!currentPassword.valid || !newPassword.valid || !confirmPassword.valid) || (newPassword.value !== confirmPassword.value) }>
                                        Change Password
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