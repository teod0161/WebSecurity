import React, {Component} from 'react';
import sanitizeHtml from 'sanitize-html';
import draftToHtml from "draftjs-to-html";
import axios from 'axios';
import qs from 'qs';
import {Redirect} from "react-router-dom";

export default class Article extends Component {


    constructor(props) {
        super(props);
        this.state = {
            loading: true,
            article: null,
            comment: {
                touched: false,
                value: props.isLoggedIn ? '' : 'You cannot comment if you are not logged in'
            },
            shouldChangePassword: false
        };
    }

    //xss
    getArticleData(data) {
        return sanitizeHtml(draftToHtml(data));
    }

    async submitComment() {
        const {comment, article} = this.state;
        const {value} = comment;

        const data = qs.stringify({
            COMMENT_DATA: value,
            AUTH_TOKEN: this.props.authToken,
            ARTICLE_ID: article.id
        });

        if (!process.env.REACT_APP_API_URL) {
            throw new Error('REACT_APP_API_URL missing')
        }

        try {
            await axios.post(`${process.env.REACT_APP_API_URL}/article/comment.php`, data);
            const response = await axios.get(`${process.env.REACT_APP_API_URL}/article/fetch_comments.php?id=${article.id}`);
            this.setState({article: {...article, comments: response.data},
                comment: {
                    touched: false,
                    value: this.props.isLoggedIn ? '' : 'You cannot comment if you are not logged in'
                }
            });
        } catch (err) {
            console.log(err);
        }
    }

    async componentWillMount() {
        if (!process.env.REACT_APP_API_URL) {
            throw new Error('REACT_APP_API_URL missing')
        }
        if (window.localStorage.getItem("AUTH_TOKEN")) {
            try {
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
            const {id} = this.props.match.params;
            try {
                const {data} = await axios.get(`${process.env.REACT_APP_API_URL}/article/get_one.php?id=${id}`);
                this.setState({article: data, loading: false})
            } catch (err) {
                console.log(err);
            }

    }

    render() {
        const {loading, article, comment, shouldChangePassword} = this.state;
        const {isLoggedIn} = this.props;

        return (
            shouldChangePassword ? <Redirect to="/change-password"/> :
            <div className="section">
                {loading && <p>loading</p>}
                {
                    !loading &&
                    <div className="columns">
                        <div className="column is-5 is-offset-1">
                            <div className="content">
                                <h2>{article.title}</h2>
                                <div
                                    dangerouslySetInnerHTML={{__html: this.getArticleData(JSON.parse(article.article_data))}}/>
                            </div>
                        </div>
                        <div className="column is-4 is-offset-1">
                            <div className="media">
                                <div className="media-left">
                                    <div className="image is-64x64">
                                        <img src={`${process.env.REACT_APP_API_URL}/public/images/${article.avatar_path}`}/>
                                    </div>
                                </div>
                                <div className="media-content">
                                    <span><strong>{article.title}</strong></span>
                                    <br/>
                                    <span className="tag is-light">{article.username}</span>
                                </div>
                            </div>
                            <br/>
                            <hr/>
                            <div className="field">
                                <span><small>Comment below</small></span>
                                <div className="control">
                                    <textarea className={`textarea ${!isLoggedIn && 'is-small'}`} disabled={!isLoggedIn}
                                              value={comment.value} onChange={e => this.setState({
                                        comment: {
                                            touched: true,
                                            value: e.target.value
                                        }
                                    })}>
                                    </textarea>
                                </div>
                            </div>
                            {
                                isLoggedIn && <div className="field">
                                    <div className="control">
                                        <a className={`${!comment.touched ? 'disabled-link' : null}`}
                                           onClick={() => this.submitComment()}>Submit comment</a>
                                    </div>
                                </div>
                            }
                            {
                                article.comments.length > 0 && <hr/>
                            }
                            {
                                article.comments.map((comment, key) => {
                                    return (
                                        <div key={key} className="field">
                                            <span><small>{comment.username}</small></span>
                                            <div className="control">
                                                <div className="content">
                                                    <p>
                                                        {comment.comment}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    )
                                })
                            }
                        </div>
                    </div>
                }
            </div>
        )
    }

}