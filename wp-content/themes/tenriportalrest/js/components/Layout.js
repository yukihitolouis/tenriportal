import React from "react"
import { connect } from "react-redux"

import { fetchUser } from "../actions/userActions"
import { fetchBooks } from "../actions/postsActions"

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

  fetchBooks() {
    this.props.dispatch(fetchBooks())
  }

  render() {
    const { user, posts } = this.props;

    if (!posts.length) {
      return <button onClick={this.fetchBooks.bind(this)}>load books</button>
    }

    if (posts.length) {
      var listItems = this.props.posts.map((post) => {
        return (
          <li key={post.id}>
            {post.title.rendered}
            <img border="0" src={post._embedded['wp:featuredmedia'][0]['media_details']['sizes']['thumbnail']['source_url']}/>
          </li>
        );
      });
    }

    return (
      <ul>
        {listItems}
      </ul>
    );
  }
}
