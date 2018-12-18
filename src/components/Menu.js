import React, {Component} from 'react';
import {Link} from 'react-router-dom'
import {isLoggedIn} from '../App'
import cx from 'classnames'

export default class Menu extends Component {
    render() {
        const {activeClass} = this.props;
        let isMyArticlesActive = false;
        let isArticlesActive = false;
        if (activeClass === 'articles') {
            isArticlesActive = true;
        } else if (activeClass === 'my-articles') {
            isMyArticlesActive = true;
        }
        return (
            <aside className="menu">
                <p className="menu-label">Home</p>
                <ul className="menu-list">
                    {/* why do you need an extension for using classnames ? */}
                    {/* <li><a className={cx({'is-active': isArticlesActive})}><Link to={"/"}>Articles</Link></a></li> */}
                    <li><a className={`${isArticlesActive && 'is-active'}`}><Link to={"/"}>Articles</Link></a></li>
                </ul>
                {!isLoggedIn() ? (
                    <div>
                        <p className="menu-label">User</p>
                        <ul className="menu-list">
                            <li><a><Link to={"/signup"}>Signup</Link></a></li>
                            <li><a><Link to={"/login"}>Log in</Link></a></li>
                        </ul>
                    </div>
                ) : (
                    <div>
                        <p className="menu-label">Profile</p>
                        <ul className="menu-list">
                            <li><a><Link to={"/profile"}>Edit Profile</Link></a></li>
                            <li>
                                <a><Link to={"/change-password"}>Change Password</Link></a>
                            </li>
                        </ul>
                        <p className="menu-label">My articles</p>
                        <ul className="menu-list">
                            <li><a><Link to={"/submit"}>Create new article</Link></a></li>
                            <li><a className={cx({'is-active': isMyArticlesActive})}><Link to={"/my-articles"}>Show my articles</Link></a></li>
                        </ul>
                    </div>
                )}
            </aside>

        )
    }

}