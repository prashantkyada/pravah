<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .order-form {
            width: 148mm; /* A5 width */
            height: 210mm; /* A5 height */
            margin: 20px auto;
            padding: 0px;
            background: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
	width:100%;
}

.logo {
    flex: 0 0 auto; /* Ensures the logo doesnt stretch */
    text-align: left;
	width:40%;
}

.info {
    flex: 1; /* Takes up remaining space */
    text-align: right;
    font-size: 14px;
	width:40%;

}
		.gst_no {
            text-align: right;
			margin-right: 50px;
        }
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .form-table th, .form-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        .form-table th {
            background-color: #f0f0f0;
        }
		.form-table td {
			text-align: center; /* Centers text horizontally */
			vertical-align: middle; /* Centers text vertically */
		}
        .totals {
            margin-top: 20px;
        }
    </style>
	
       <!--Body content-->
      <div class="order-form">
      <div class="header">
			<div class="logo">	
				<img src="images/Pravah Logo CDR-11.png" alt="logo">
			</div>
			<div class="info">
				<p><strong>Date:</strong> 07-03-2025</p>
				<p><strong>Order Form No:</strong> order no</p>
			</div>
		</div>
		<div class="header_name">
			<div class="party_name"><strong>M/s:</strong> party name </div>
			<div class="infoheder">
				<div class="trans"><strong>Trans.:</strong>ABCDFG </div>
				<div class="gst_no"><strong>GST No:</strong>123456789 </div>
			</div>
		</div>
        <table class="form-table">
            <thead>
                <tr>
                    <th>Particulars</th>
                    <th>Order Qty</th>
                    <th>Pcs</th>
                    <th>C/R</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
            	<tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
        
          </tbody>
        </table>
    </div>
    