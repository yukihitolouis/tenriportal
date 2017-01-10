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
    console.log('posts', posts);
    if (!posts.acf) {
      return <button onClick={this.fetchPosts.bind(this)}>load posts</button>
    }

    return <div>
      <h1>{user.name}</h1>
      <div>{posts.title.rendered}</div>
      <div>{posts.acf.subtitle}</div>
        <input type="checkbox" checked={posts.acf.is_tenrikyo_book}/>
    </div>
  }
}
