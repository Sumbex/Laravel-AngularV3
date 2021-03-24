import { CumpleanioService } from './../../servicios/cumpleanio.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-admin-cumpleanios',
  templateUrl: './admin-cumpleanios.component.html',
  styleUrls: ['./admin-cumpleanios.component.css']
})
export class AdminCumpleaniosComponent implements OnInit {

  constructor(public cumpleanio: CumpleanioService) { }

  cumpleanios=[
    {
      'nombres':'',
      'a_paterno':'',
      'a_materno':'',
      'fecha_nacimiento': ''
    }
  ];

  ngOnInit() {
    this.listado();
  }

  listado(){
    this.cumpleanio.listado().subscribe((res)=>{
      this.cumpleanios = res;
    });
  }

}
