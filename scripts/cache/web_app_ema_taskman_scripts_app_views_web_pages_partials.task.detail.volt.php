<div class="task-detail task-detail-wrapper">
    <div class="section-wrapper">
        <div class="section-header border-bottom">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1 justify-content-start d-flex align-items-center">
                    <a href="javascript:;" class="dropdown-toggle btn-task-priority cursor-pointer btn text-capitalize" data-toggle="dropdown"><i class="icon-arrow-up7 text-muted"></i></a>
                    <div class="dropdown-menu dropdown-menu-condensed">
                        <a class="dropdown-item btn-action-task menu-item-priority" data-priority="3" data-action="priority"><i class="icon-arrow-up7 text-danger"></i> Urgent</a>
                        <a class="dropdown-item btn-action-task menu-item-priority" data-priority="2" data-action="priority"><i class="icon-arrow-up7 text-warning"></i> High</a>
                        <a class="dropdown-item btn-action-task menu-item-priority" data-priority="1" data-action="priority"><i class="icon-arrow-up7 text-muted"></i> Normal</a>
                        <a class="dropdown-item btn-action-task menu-item-priority" data-priority="0" data-action="priority"><i class="icon-arrow-down7 text-success"></i> Low</a>
                    </div>
                    <a class="btn cursor-pointer btn-attachment text-muted" title="Add File"><i class="icon-attachment"></i></a>
                </div>
                <div class="border-left">
                    <a class="btn cursor-pointer btn-close-detail text-muted" title="Close Detail"><i class="icon-cross2"></i></a>
                </div>
            </div>
        </div>
        <div class="section-content">
            <div class="task-title-wrapper">
                <div class="task-status-cont">
                    <a href="javascript:;" class="dropdown-toggle btn-task-status btn-task-detail-action cursor-pointer text-muted" data-toggle="dropdown"><i class="far fa-check-circle text-muted"></i></a>
                    <div class="dropdown-menu dropdown-menu-condensed">
                        <a class="dropdown-item btn-action-task menu-item-status" data-status="0" data-action="status"><i class="far fa-check-circle text-muted"></i> Todo</a>
                        <a class="dropdown-item btn-action-task menu-item-status" data-status="2" data-action="status"><i class="icon-circle2 text-info"></i> In Progress</a>
                        <a class="dropdown-item btn-action-task menu-item-status" data-status="1" data-action="status"><i class="icon-checkmark-circle text-success"></i> Complete</a>
                    </div>
                </div>
                <div class="task-title-cont">
                    <div class="task-title-frame t-name">Task Name</div>
                    <textarea class="text-title text-autosize" placeholder="Task Name"></textarea>
                </div>
            </div>
            <div class="task-action-wrapper">
                <div class="task-assigne-cont">
                    <a href="javascript:;" data-target="assigneeMenu"  id="dropdownMenuAssignee" aria-haspopup="true" aria-expanded="false" class="task-assignee-cont btn-task-detail-action dropdown-toggle"  data-toggle="dropdown">
                        <div class="task-icon-label mr-1"><i class="far fa-user text-muted"></i></div>
                        <div class="task-assignee">Add Assignee</div>
                    </a>
                    <div class="dropdown-menu" id="assigneeMenu" aria-labelledby="dropdownMenuAssignee">
                        <div class="text-muted small p-4 text-center">
                            coming soon
                        </div>
                    </div>
                </div>
                <div class="div-border"></div>
                <div>
                    <a href="javascript:;" data-target="duedateMenu"  id="dropdownMenuDate" aria-haspopup="true" aria-expanded="false" class="task-date-cont btn-task-detail-action dropdown-toggle"  data-toggle="dropdown">
                        <div class="task-icon-label mr-1"><i class="far fa-calendar-check text-muted"></i></div>
                        <div class="task-due-date">Due Date</div>
                    </a>
                    <div class="dropdown-menu" id="duedateMenu" aria-labelledby="dropdownMenuDate">
                        <div class="p-1">
                            <div class="TaskDetail_datePickerMenu mb-2"></div>
                            <div class="d-flex justify-content-between p-2">
                                <button data-action="today" class="border btn btn-xs btn-due-date">Today</button>
                                <button data-action="tomorrow" class="border btn btn-xs btn-due-date">Tomorrow</button>
                                <button data-action="none" class="border btn btn-xs btn-due-date">None</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="task-tag-wrapper d-none">
                <a href="javascript:;" data-target="tagMenu"  id="dropdownMenuTag" aria-haspopup="true" aria-expanded="false" class="task-tag-cont btn-task-detail-action dropdown-toggle"  data-toggle="dropdown">
                    <div class="task-icon-label mr-1"><i class="icon-price-tag3 text-muted"></i></div>
                    <div class="task-tag">Add Tag</div>
                </a>
                <div class="dropdown-menu" id="tagMenu" aria-labelledby="dropdownMenuTag">
                    <div class="text-muted small p-4 text-center">
                        coming soon
                    </div>
                </div>
            </div>
            <div class="task-description-wrapper mb-2">
                <div class="task-description-frame t-description">Add description</div>
                <textarea class="text-description text-autosize" placeholder="Add description"></textarea>
                <div class="task-description-action-wrapper">
                    <div>
                        <button type="button" disabled class="btn btn-save-description btn-xs btn-success">Save</button>
                        <button type="button" class="btn btn-cancel-description btn-xs btn-outline bg-slate-600 text-slate-600 border-slate-600">Cancel</button>
                    </div>
                </div>
            </div>
            <div class="task-files-wrapper mb-2">
                <div class="task-file-images row mb-2"></div>
                <div class="task-file-other"></div>
            </div>
            <div class="task-comments-wrapper"></div>
        </div>
        <div class="section-footer border-top">
            <div class="task-comment-box-wrapper">
                <div class="">
                    <img src="<?= $accountUrl ?>pic/acc/<?= $account->uid ?>/40/40" class="img-fluid rounded-circle border" width="40" height="40" alt="">
                </div>
                <div class="task-comment-box">
                    <textarea class="text-comment text-autosize" placeholder="Add Comment"></textarea>
                    <div class="task-comment-action-wrapper">
                        <div>
                            <button type="button" disabled class="btn btn-send-comment btn-xs btn-success">Send</button>
                            <button type="button" class="btn btn-cancel-comment btn-xs btn-outline bg-slate-600 text-slate-600 border-slate-600">Cancel</button>
                        </div>
                        <!-- <button type="button" class="btn btn-xs btn-attach-comment">Attachment <i class="icon-attachment"></i></button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
