<?php
/**
* Groups Table class
* 
* @package Comment_Rating_Field_Pro
* @author Tim Carr
* @version 1.0
*/

class Comment_Rating_Field_Pro_Groups_Table extends WP_List_Table {

	/**
     * Holds the class object.
     *
     * @since 3.2.6
     *
     * @var object
     */
    public static $instance;
    
	/**
	* Constructor, we override the parent to pass our own arguments
	*
	* We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
	*
    * @since 3.2.0
    */
	function __construct() {

		parent::__construct( array(
			'singular'	=> 'group',
			'plural' 	=> 'groups',
			'ajax'		=> false
		) );

	}

	/**
	* Defines the message to display when no items exist in the table
	*
    * @since 3.2.0
	*
	* @return No Items Message
	*/
	function no_items() {

		_e( 'No rating field groups exist.', 'comment-rating-field-pro-plugin' );
		echo ( '<br /><a href="admin.php?page=comment-rating-field-pro-plugin-rating-fields&cmd=form" class="button">' . __( 'Create first rating field group.', 'comment-rating-field-pro-plugin' ).'</a>' );
	
	}
	 
	/**
 	* Define the columns that are going to be used in the table
 	*
    * @since 3.2.0
    *
 	* @return array $columns, the array of columns to use with the table
 	*/
	function get_columns() {

		return array(
			'cb' 					=> '<input type="checkbox" class="toggle" />',
			'col_field_name' 		=> __( 'Field Group Name', 'comment-rating-field-pro' ),
			'col_field_fields'		=> __( 'Fields', 'comment-rating-field-pro' ),
			'col_field_schema_type'	=> __( 'Schema Type', 'comment-rating-field-pro' ),
		);

	}
	
	/**
 	* Decide which columns to activate the sorting functionality on
 	*
    * @since 3.2.0
    *
 	* @return array $sortable, the array of columns that can be sorted by the user
 	*/
	public function get_sortable_columns() {
		return $sortable = array(
			'col_field_label' 		=> array( 'name', true ),
			'col_field_schema_type' => array( 'schema_type', true ),
		);
	}
	
	/**
	* Overrides the list of bulk actions in the select dropdowns above and below the table
	*
    * @since 3.2.0
	*/
	public function get_bulk_actions() {
		return array(
			'delete' => __( 'Delete', 'comment-rating-field-pro' ),
		);
	}
	
	/**
 	* Prepare the table with different parameters, pagination, columns and table elements
 	*
    * @since 3.2.0
 	*/
	function prepare_items() {
		
		global $_wp_column_headers;
		
		$screen = get_current_screen();
		
		// Get params
		$search 	= ( isset( $_REQUEST['s'] ) ? $_REQUEST['s'] : '' );
		$order_by 	= ( isset( $_GET['orderby'] ) ? $_GET['orderby'] : 'keyword' );
  		$order 		= ( isset( $_GET['order'] ) ? $_GET['order'] : 'ASC' );
		
		// Adjust as necessary to display the required number of rows per screen
		$rows_per_page = 10;

		// Get all records
		$total = Comment_Rating_Field_Pro_Groups::get_instance()->total( $search );
		
		// Define pagination if required
		$paged = 1;
		if ( isset( $_GET['paged'] ) && ! empty( $_GET['paged'] ) ) {
			$paged = absint( $_GET['paged'] );
		}
		$this->set_pagination_args( array(
			'total_items' 	=> $total,
			'total_pages' 	=> ceil( $total / $rows_per_page ),
			'per_page' 		=> $rows_per_page,
		) );
		
		// Set table columns and rows
		$columns = $this->get_columns();
  		$hidden  = array();
  		$sortable = $this->get_sortable_columns();
  		$this->_column_headers = array( $columns, $hidden, $sortable );
  		$this->items = Comment_Rating_Field_Pro_Groups::get_instance()->get_all( $order_by, $order, $paged, $rows_per_page, $search );

	}

	/**
	* Display the rows of records in the table
	*
    * @since 3.2.0
    *
	* @return string, echo the markup of the rows
	*/
	function display_rows() {

		// Get rows and columns
		$records = $this->items;
		list( $columns, $hidden ) = $this->get_column_info();
		
		// Bail if no records found
		if ( empty( $records) ) {
			return;
		}

		// Iterate through records
		foreach ( $records as $key => $record ) {
			// Start row
			echo ('<tr id="record_' . $record['groupID'] . '"' . ( ( $key % 2 == 0 ) ? ' class="alternate"' : '') . '>' );

			// Iterate through columns
			foreach ( $columns as $column_name => $display_name ) {
				switch ( $column_name ) {

					/**
					* Checkbox
					*/
					case 'cb': 
						echo ( '<th scope="row" class="check-column"><input type="checkbox" name="ids[' . absint( $record['groupID'] ) . ']" value="' . absint( $record['groupID'] ) . '" /></th>' ); 
						break;
						
					/**
					* Field Name
					*/
					case 'col_field_name': 
							echo ( '<td class="' . $column_name . ' column-' . $column_name . '">
									<strong>
										<a href="admin.php?page=comment-rating-field-pro-plugin-rating-fields&cmd=form&id=' . absint( $record['groupID'] ) . '" title="' . __( 'Edit this item', 'comment-rating-field-pro-plugin' ) . '">
											' . $record['name'] . '
										</a>
									</strong>
									<div class="row-actions">
										<span class="edit">
											<a href="admin.php?page=comment-rating-field-pro-plugin-rating-fields&cmd=form&id=' . absint( $record['groupID'] ) . '" title="' . __( 'Edit this item', 'comment-rating-field-pro-plugin' ) . '">
											' . __( 'Edit', 'comment-rating-field-pro-plugin' ) . '
											</a> | 
										</span>
										<span class="trash">
											<a href="admin.php?page=comment-rating-field-pro-plugin-rating-fields&cmd=delete&id=' . absint( $record['groupID'] ) . '" title="' . __( 'Delete this item', 'comment-rating-field-pro-plugin' ).'" class="delete">
											' . __( 'Delete', 'comment-rating-field-pro-plugin') . '
											</a>
										</span>
									</div>
								</td>'); 

							break;

					/**
					* Number of Fields
					*/
					case 'col_field_fields': 
						echo ( '<td class="' . $column_name . ' column-' . $column_name . '">
									' . count ( $record['fields'] ) . '
								</td>' ); 
						break;

					/**
					* Schema Type
					*/
					case 'col_field_schema_type': 
						echo ('	<td class="' . $column_name . ' column-' . $column_name . '">
									' . $record['schema_type'] . '
								</td>'); 
						break;

				}
			}

			// End row
			echo (' </tr>' );

		}
	}

	/**
     * Returns the singleton instance of the class.
     *
     * @since 3.2.6
     *
     * @return object Class.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
            self::$instance = new self;
        }

        return self::$instance;

    }

}