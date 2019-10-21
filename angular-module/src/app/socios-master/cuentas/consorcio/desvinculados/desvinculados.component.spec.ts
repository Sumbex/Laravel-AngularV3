import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DesvinculadosComponent } from './desvinculados.component';

describe('DesvinculadosComponent', () => {
  let component: DesvinculadosComponent;
  let fixture: ComponentFixture<DesvinculadosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DesvinculadosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DesvinculadosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
