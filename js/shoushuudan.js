// Parameters
var availableTime = 15000; // [ms]
var circle_radius = 30;

// array containing member objects
var members = [];
var active_member_index = 0;

var state = "waiting";
var timeout;

// Might be good not to have global
var situation_image;
var canvas;
var context;

function getMousePos(canvas, evt) {
  var rect = canvas.getBoundingClientRect();
  return {
    x: evt.clientX - rect.left,
    y: evt.clientY - rect.top
  };
}


function map(val, in_min, in_max, out_min, out_max) {
  // Linear mapping
  return (val - in_min) * (out_max - out_min) / (in_max - in_min) + out_min;
}

function Member(name, color) {
  // Member class

  // Assigns a name, and stores points
  this.name = name;
  this.points = [];
  this.color = color;

  this.displayPoints = function() {
    // displays where the member has clicked

    for(var point_index = 0; point_index<this.points.length; point_index++) {

      var point_x = map(this.points[point_index].x,0,1,0, canvas.width);
      var point_y = map(this.points[point_index].y,0,1,0, canvas.height);

      context.lineWidth=5;
      context.strokeStyle=this.color;
      context.beginPath();
      context.arc(point_x,point_y,circle_radius,0,2*Math.PI);
      context.stroke();
    }
  }
}

function get_members_from_table() {
  // get the members names from the table and create a member object with it

  for (var row_index = 0; row_index < scores_table.rows.length; row_index++) {
    // The members name is stored in the column 0
    var member_name = scores_table.rows.item(row_index).cells.item(0).innerHTML;
    var member_color = scores_table.rows.item(row_index).style.color;
    members.push(new Member(member_name,member_color));
  }
}



function next_member() {

  clearTimeout(timeout);
  state = "waiting";
  active_member_index ++;

  // If all members are finished, show summary
  if(active_member_index >= members.length) {
    state = "summary";

    next_button.disabled = true;
    start_button.disabled = true;
    undo_button.disabled = true;
    submit_button.disabled = false;

    show_summary();
  }
  else {
    // The game will continue because there are still members who haven't played
    start_button.disabled = false;
    undo_button.disabled = true;

    show_only_active_member();
    show_waiting();
  }
}


function show_summary(){

  // Show the points and score of all members
  draw_image_on_canvas();

  // display the points on the canvas
  members.forEach(function(member) {
    member.displayPoints();
  });

  // display the complete score table
  for(var member_index = 0; member_index<members.length; member_index++) {
    scores_table.rows.item(member_index).cells.item(0).style.display="table-cell";
    scores_table.rows.item(member_index).cells.item(1).style.display="table-cell";
  }
}

function show_waiting(){

  // Show the waiting image

  context.clearRect(0, 0, canvas.width, canvas.height);

  // Not showing anything if members not defined yet. NOT CLEAN
  if(typeof members[active_member_index] !== 'undefined'){
    context.fillStyle = members[active_member_index].color;
    context.font = "30px Arial";
    context.textAlign="center";
    context.fillText(members[active_member_index].name+ "'s turn",0.5*canvas.width,0.4*canvas.height);
    context.fillText("press \"start\" to begin",0.5*canvas.width,0.6*canvas.height);
  }
}

function refresh_visuals() {
  if(state == "input") {

    // Update the table needed?
    scores_table.rows.item(active_member_index).cells.item(1).innerHTML = members[active_member_index].points.length;

    // clear screen and display points
    draw_image_on_canvas();
    members[active_member_index].displayPoints();
  }
  else if (state =="waiting") {

    show_waiting();

  }
  else if (state =="summary") {
    show_summary();
  }
}

function draw_image_on_canvas(){

  // Draws image on canvas and make sure Aspect ratio is kept

  var image_aspect_ratio = situation_image.width / situation_image.height;
  var canvas_aspect_ratio = canvas.width / canvas.height;
  var image_width, image_height;

  console.log(image_aspect_ratio);

  if(image_aspect_ratio < canvas_aspect_ratio){
    image_height = canvas.height;
    image_width = image_height*image_aspect_ratio;
  }
  else if(image_aspect_ratio > canvas_aspect_ratio){
    image_width = canvas.width;
    image_height = image_width/image_aspect_ratio;
  }
  else {
    image_height = canvas.height;
    image_width = canvas.width;
  }

  context.drawImage(situation_image, 0.5*canvas.width-0.5*image_width, 0.5*canvas.height-0.5*image_height, image_width, image_height);
}


function show_only_active_member(){
  for(var member_index = 0; member_index<members.length; member_index++) {

    if(member_index!=active_member_index) {
      scores_table.rows.item(member_index).cells.item(0).style.display="none";
      scores_table.rows.item(member_index).cells.item(1).style.display="none";
    }
    else {
      scores_table.rows.item(member_index).cells.item(0).style.display="table-cell";
      scores_table.rows.item(member_index).cells.item(1).style.display="table-cell";
    }
  }
}

function resize_canvas(){
  // Finally nailed it
  var container = canvas.parentNode;
  canvas.width = container.offsetWidth;
  canvas.height = container.offsetHeight;
}



window.onload = function () {

  // Get elements
  var scores_table = document.getElementById('scores_table');
  situation_image = document.getElementById('situation_image');
  canvas = document.getElementById('canvas');
  context = canvas.getContext('2d'); // CANNOT BE A VARIABLE OF THIS SCOPE

  var submit_button = document.getElementById("submit_button");
  var undo_button = document.getElementById("undo_button");
  var start_button = document.getElementById("start_button");
  var next_button = document.getElementById("next_button");

  resize_canvas();
  get_members_from_table();
  show_only_active_member();
  show_waiting();

  submit_button.disabled = true;
  undo_button.disabled = true;


  // Connect events

  // Start button
  start_button.onclick = function() {
    if(state == "waiting") {

      state = "input";
      draw_image_on_canvas();

      start_button.disabled = true;
      undo_button.disabled = false;

      timeout = window.setTimeout(next_member, 15000);
    }
  };

  // Next member button
  next_button.onclick = function() {
    if(confirm('ホンマに？')) next_member();

  };

  undo_button.onclick = function() {
    if(state == "input") {
      members[active_member_index].points.pop();

      refresh_visuals();
    }
  };

  submit_button.onclick = function() {

    if(confirm('ホンマに？')){
      // Prevent submitting twice
      submit_button.disabled = true;

      // Add results image to form
      var dataURL = canvas.toDataURL("image/png");
      document.getElementById('image_input').value = dataURL;

      // Fill the submit form with members points
      var submit_form = document.getElementById("submit_form");
      for(var member_index = 0; member_index<members.length; member_index++) {

        var new_name_input = document.createElement("input");
        new_name_input.type="hidden";
        new_name_input.name="members_name[]";
        new_name_input.value=members[member_index].name;
        submit_form.appendChild(new_name_input);

        var new_score_input = document.createElement("input");
        new_score_input.type="hidden";
        new_score_input.name="members_points[]";
        new_score_input.value=members[member_index].points.length;
        submit_form.appendChild(new_score_input);
      }

      submit_form.submit();
    }


  };

  document.getElementById("return_button").onclick = function() {

    if(confirm('ホンマに？')){
      var return_form = document.getElementById("return_form");
      return_form.submit();
    }

  };

  canvas.addEventListener('click',function(evt) {

    if(state == "input") {

      var mouse_pos_px = getMousePos(canvas,evt);

      var mouse_pos_percent = {
        x: map(mouse_pos_px.x,0,canvas.width,0,1),
        y: map(mouse_pos_px.y,0,canvas.height,0,1)
      };

      members[active_member_index].points.push(mouse_pos_percent);

      refresh_visuals();
    }
  },false);

} // End of onload


window.onresize = function(event) {
  resize_canvas();
  refresh_visuals();
};
