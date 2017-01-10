import React from "react"
import { connect } from "react-redux"

import { fetchUser } from "../actions/userActions"
import { fetchPosts } from "../actions/postsActions"

@connect((store) => {
  return {
    user: store.user.user,
    userFetched: store.user.fetched,
    posts: store.posts.posts,
  };
})
export default class Layout extends React.Component {
  componentWillMount() {
    this.props.dispatch(fetchUser())
  }

  fetchPosts() {
    this.props.dispatch(fetchPosts())
  }

  render() {
    const { user, posts } = this.props;

    if (!posts.acf) {
      return <button onClick={this.fetchPosts.bind(this)}>load posts</button>
    }

    return <div>
      <h1>{user.name}</h1>
      <div>{posts.acf.spell}</div>
        <input type="checkbox" checked={posts.acf.is_curse}/>
    </div>
  }
}
