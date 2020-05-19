import { Component, OnInit, Inject } from '@angular/core';
import {MatDialog, MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';

@Component({
  selector: 'override-authorization',
  templateUrl: './override-authorization.component.html',
  styleUrls: ['./override-authorization.component.css']
})
export class OverrideAuthorizationComponent implements OnInit {

  authorization: string;

  constructor(public dialogRef: MatDialogRef<OverrideAuthorizationComponent>, @Inject(MAT_DIALOG_DATA) data) { }

  close() {
    this.dialogRef.close();
  }

  override(){
    this.dialogRef.close(this.authorization);
  }

  ngOnInit(): void {
  }

}
