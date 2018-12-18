import React, {Component} from 'react';
import Editor from '../components/Editor';
import {Redirect} from 'react-router-dom';
import {convertToRaw} from 'draft-js'
import qs from 'qs';
import axios from 'axios';


export default class Submit extends Component {

    state = {
        title: {
            value: '',
            touched: false,
            valid: false
        },
        description: {
            value: '',
            touched: '',
            valid: false,
        },
        shouldChangePassword: false
    };

    async componentWillMount() {

        if (!process.env.REACT_APP_API_URL) {
            throw new Error('REACT_APP_API_URL missing')
        }

        try {
            //request forgery ?
            await axios.post(`${process.env.REACT_APP_API_URL}/user/is_password_resetted.php`, qs.stringify({
                AUTH_TOKEN: window.localStorage.getItem("AUTH_TOKEN")
            })).then(response => {
                if (response.status === 200 && !response.data) {
                    this.setState({shouldChangePassword: true});
                }
            });
        } catch (err) {
            console.log(err);
        }
    }

    async saveArticle() {
        const {title, description} = this.state;
        //xss
        const editorState = this.editor.state.editorState;
        const data = JSON.stringify(convertToRaw(editorState.getCurrentContent()));

        if (!process.env.REACT_APP_API_URL) {
            throw new Error('REACT_APP_API_URL missing')
        }
        if (window.localStorage.getItem("AUTH_TOKEN")) {
            try {
                await axios.post(`${process.env.REACT_APP_API_URL}/article/add.php`, qs.stringify({
                    AUTH_TOKEN: this.props.authToken,
                    article_data: data,
                    title: title.value,
                    description: description.value,
                })).then(response => {
                    if (response.status === 200) {
                        this.props.history.push('/');
                    }
                });
            } catch (err) {
                console.log(err);
            }
        }
    }

    handleChangeTitle(title) {
        this.setState({title: {value: title, touched: true, valid: title !== '' && title !== ' '}});
    }

    handleChangeDescription(description) {
        this.setState({
            description: {
                value: description,
                touched: true,
                valid: description !== '' && description !== ' '
            }
        });
    }

    render() {
        const {title, description, shouldChangePassword} = this.state;

        return (
            shouldChangePassword ? <Redirect to="/change-password"/> :
                <div className="section">
                <div className="columns">
                    <div className="column is-3 is-offset-1">
                        <div className="field">
                            <div className="control">
                                <div className="content">
                                    <h6>Welcome {}</h6>
                                    <p>
                                        <small><strong>You are about to submit a public article.</strong></small>
                                    </p>
                                    <p>
                                        <small>Please remember everyone will be able to see this article.</small>
                                        <br/>
                                        <small>In addition, users will be able to submit comments.</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="column is-5 is-offset-1">
                        <div className="field">
                            <div className="control">
                                <input className={`input ${title.touched && !title.valid && 'is-danger'}`}
                                       placeholder="Article Title" value={title.value}
                                       onChange={e => this.handleChangeTitle(e.target.value)}/>
                            </div>
                        </div>
                        <div className="field">
                            <div className="control">
                                <textarea value={description.value}
                                          className={`textarea ${description.touched && !description.valid && 'is-danger'}`}
                                          onChange={e => this.handleChangeDescription(e.target.value)}
                                          placeholder="Short description"
                                />
                            </div>
                        </div>
                        <div className="field">
                            <Editor ref={editor => this.editor = editor}/>
                        </div>
                        <div className="field is-grouped">
                            <div className="control is-expanded">
                                <button className="button is-fullwidth" onClick={() => this.saveArticle()} disabled={!title.valid || !description.valid}>SUBMIT
                                </button>
                            </div>
                            <div className="control is-expanded">
                                <button className="button is-fullwidth" disabled={true}>PREVIEW</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }

}