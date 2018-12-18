import React, {Component} from 'react';
import {Route, Redirect} from 'react-router-dom'

//routes
import Login from './pages/Login';
import Signup from './pages/Signup';
import Home from './pages/index';
import Submit from './pages/Submit';
import Article from './pages/Article';
import Profile from './pages/Profile';
import ChangePassword from './components/ChangePassword';
import ForgotPassword from './pages/ForgotPassword';
import MyArticles from './pages/MyArticles';

import protectedPage from './components/Protected';

const SubmitPage = protectedPage(Submit, true);
const MyArticlesPage = protectedPage(MyArticles, true);
const ArticlePage = protectedPage(Article, false);
const ProfilePage = protectedPage(Profile, true);

export const isLoggedIn = () => {
    let token = window.localStorage.getItem('AUTH_TOKEN')
    return token !== null
}

class App extends Component {
    async componentWillMount() {
        const token = localStorage.getItem('token');
        if (token) {
            this.setState({loggedIn: true});
        }
    }

    render() {
        return (
            <div>
                <Route path="/login" component={Login}/>
                <Route path="/signup" component={Signup}/>
                <Route path="/submit" component={SubmitPage}/>
                <Route path="/article/:id" component={ArticlePage}/>
                <Route path="/profile" component={ProfilePage}/>
                <Route path="/my-articles" component={MyArticlesPage}/>
                <Route path="/change-password" component={ChangePassword}/>
                <Route path="/forgot-password" component={ForgotPassword}/>
                <Route path="/" exact component={Home}/>

            </div>
        );
    }
}

export default App;
