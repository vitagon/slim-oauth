import React from 'react';
import styles from './FullPage.module.scss';

class FullPage extends React.Component<any, any> {

  constructor(props) {
    super(props);
    this.state = {
    }
  }

  render() {
    return (
      <div className={styles.wrap}>
        {this.props.children}
      </div>
    )
  }
}

export default FullPage;
